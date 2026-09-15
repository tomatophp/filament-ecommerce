<?php

use TomatoPHP\FilamentEcommerce\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\ListOrders;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();

    // The ordering settings default to company / branch #1.
    $this->company = CompanyFactory::new()->create();
    $this->branch = BranchFactory::new()->for($this->company)->create();
});

it('imports an order from text', function () {
    ProductFactory::new()->create(['sku' => 'TOMATO1', 'price' => 100, 'vat' => 10]);

    livewire(ListOrders::class)
        ->callTableAction('import', data: [
            'data' => "name: Ali\nphone: 01000000001\naddress: Cairo\nsource: system\nitems: TOMATO1*2",
        ])
        ->assertHasNoTableActionErrors();

    $order = Order::query()->where('phone', '01000000001')->firstOrFail();

    expect($order->name)->toBe('Ali')
        ->and($order->ordersItems()->count())->toBe(1)
        ->and((float) $order->total)->toBe(220.0)
        ->and((float) $order->vat)->toBe(20.0)
        ->and($order->orderLogs()->count())->toBe(1);
});

it('approves a pending order', function () {
    $order = OrderFactory::new()->create(['company_id' => $this->company->id, 'branch_id' => $this->branch->id]);

    livewire(ListOrders::class)
        ->callTableAction('approved', $order);

    expect($order->refresh())
        ->status->toBe('prepared')
        ->is_approved->toBeTruthy();
});

it('changes an order status', function () {
    $order = OrderFactory::new()->create(['company_id' => $this->company->id, 'branch_id' => $this->branch->id]);

    livewire(ListOrders::class)
        ->callTableAction('status', $order, data: ['status' => 'shipped'])
        ->assertHasNoTableActionErrors();

    expect($order->refresh()->status)->toBe('shipped')
        ->and($order->orderLogs()->count())->toBe(1);
});
