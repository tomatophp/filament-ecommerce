<?php

use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\Pages\CreateCompany;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\Pages\EditCompany;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource\Pages\CreateCoupon;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource\Pages\EditCoupon;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\EditOrder;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages\CreateProduct;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages\EditProduct;
use TomatoPHP\FilamentEcommerce\Models\Company;
use TomatoPHP\FilamentEcommerce\Models\Coupon;
use TomatoPHP\FilamentEcommerce\Models\Product;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CouponFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();
});

it('creates and edits a product', function () {
    livewire(CreateProduct::class)
        ->fillForm([
            'type' => 'product',
            'name' => 'Tomato Box',
            'slug' => 'tomato-box',
            'sku' => 'TOMATO-BOX',
            'price' => 50,
            'vat' => 5,
            'discount' => 0,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $product = Product::query()->where('slug', 'tomato-box')->firstOrFail();
    expect((float) $product->price)->toBe(50.0)
        ->and($product->getTranslation('name', 'en'))->toBe('Tomato Box');

    livewire(EditProduct::class, ['record' => $product->getRouteKey()])
        ->fillForm(['price' => 75])
        ->call('save')
        ->assertHasNoFormErrors();

    expect((float) $product->refresh()->price)->toBe(75.0);
});

it('creates and edits a coupon', function () {
    livewire(CreateCoupon::class)
        ->fillForm([
            'code' => 'SAVE15',
            'type' => 'discount_coupon',
            'amount' => 15,
            'is_activated' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $coupon = Coupon::query()->where('code', 'SAVE15')->firstOrFail();
    expect((float) $coupon->amount)->toBe(15.0);

    livewire(EditCoupon::class, ['record' => $coupon->getRouteKey()])
        ->fillForm(['amount' => 25, 'type' => 'percentage_coupon'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($coupon->refresh())
        ->amount->toEqual(25)
        ->type->toBe('percentage_coupon');
});

it('keeps the coupon code unique', function () {
    CouponFactory::new()->create(['code' => 'TAKEN']);

    livewire(CreateCoupon::class)
        ->fillForm(['code' => 'TAKEN', 'type' => 'discount_coupon', 'amount' => 5])
        ->call('create')
        ->assertHasFormErrors(['code' => 'unique']);
});

it('creates and edits a company', function () {
    livewire(CreateCompany::class)
        ->fillForm([
            'name' => 'Tomato Market',
            'email' => 'hello@tomato.test',
            'phone' => '+201000000000',
            'ceo' => 'Fady Mondy',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $company = Company::query()->where('name', 'Tomato Market')->firstOrFail();

    livewire(EditCompany::class, ['record' => $company->getRouteKey()])
        ->fillForm(['name' => 'Tomato Market Ltd'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($company->refresh()->name)->toBe('Tomato Market Ltd');
});

it('edits an order', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();
    $order = OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);

    livewire(EditOrder::class, ['record' => $order->getRouteKey()])
        ->fillForm(['notes' => 'Leave at the door'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($order->refresh()->notes)->toBe('Leave at the door');
});
