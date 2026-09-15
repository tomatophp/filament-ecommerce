<?php

use Illuminate\Http\Request;
use TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\AccountFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CouponFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('gives a fixed coupon discount', function () {
    $product = ProductFactory::new()->create();
    CouponFactory::new()->create(['code' => 'FIXED10', 'type' => 'discount_coupon', 'amount' => 10]);

    $discount = FilamentEcommerce::coupon()->products([$product->id])->discount(code: 'FIXED10', total: 200);

    expect($discount)->toBe(10.0);
});

it('gives a percentage coupon discount', function () {
    $product = ProductFactory::new()->create();
    CouponFactory::new()->create(['code' => 'TEN', 'type' => 'percentage_coupon', 'amount' => 10]);

    expect(FilamentEcommerce::coupon()->products([$product->id])->discount(code: 'TEN', total: 200))->toBe(20.0);
});

it('rejects inactive, expired and unknown coupons', function () {
    CouponFactory::new()->create(['code' => 'OFF', 'is_activated' => false]);
    CouponFactory::new()->create(['code' => 'OLD', 'end_at' => now()->subDay()->toDateString()]);

    expect(FilamentEcommerce::coupon()->check('OFF'))->toBeFalse()
        ->and(FilamentEcommerce::coupon()->check('OLD'))->toBeFalse()
        ->and(FilamentEcommerce::coupon()->check('MISSING'))->toBeFalse()
        ->and(FilamentEcommerce::coupon()->discount(code: 'OLD', total: 100))->toBe(0.0);
});

it('stores an order with its items and totals', function () {
    $company = CompanyFactory::new()->create();
    BranchFactory::new()->for($company)->create();
    $account = AccountFactory::new()->create();
    $product = ProductFactory::new()->create(['price' => 100, 'vat' => 10]);

    $request = Request::create('/', 'POST', [
        'uuid' => 'TOMATO-TEST1',
        'company_id' => $company->id,
        'account_id' => ['id' => $account->id, 'name' => $account->name, 'phone' => $account->phone],
        'items' => [
            [
                'item' => ['id' => $product->id, 'name' => ['en' => 'Tomato Box']],
                'qty' => 2,
                'price' => 100,
                'discount' => 5,
                'tax' => 10,
                'total' => 205,
            ],
        ],
    ]);

    $order = FilamentEcommerce::order()->store($request);

    expect($order->exists)->toBeTrue()
        ->and($order->account_id)->toBe($account->id)
        ->and((float) $order->total)->toBe(205.0)
        ->and((float) $order->vat)->toBe(10.0)
        ->and((float) $order->discount)->toBe(5.0)
        ->and($order->ordersItems()->count())->toBe(1)
        ->and($order->ordersItems()->first()->item)->toBe('Tomato Box')
        ->and($order->orderLogs()->count())->toBe(1);
});
