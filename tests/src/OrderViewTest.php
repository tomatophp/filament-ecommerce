<?php

use TomatoPHP\FilamentEcommerce\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Models\OrdersItem;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();
});

it('renders the order view page for an order without a company', function () {
    // company_id is nullable; the summary used to read $record->company->name and failed with a 500.
    $order = OrderFactory::new()->create(['company_id' => null, 'branch_id' => null]);

    $this->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful()
        ->assertSee($order->uuid);
});

it('renders the order view page for an order with items', function () {
    // Order items store VAT in `vat`; the summary used to read the missing `tax` attribute and failed with a 500.
    $order = OrderFactory::new()->create();
    $product = ProductFactory::new()->create();

    OrdersItem::query()->create([
        'order_id' => $order->id,
        'account_id' => $order->account_id,
        'product_id' => $product->id,
        'item' => 'Tomato Hoodie',
        'price' => 45,
        'discount' => 5,
        'vat' => 2,
        'qty' => 1,
        'total' => 42,
    ]);

    $this->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful()
        ->assertSee('Tomato Hoodie');
});

it('shows the company on the order view page when the order has one', function () {
    $company = CompanyFactory::new()->create(['name' => 'Tomato Store']);
    $branch = BranchFactory::new()->for($company)->create();
    $order = OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);

    $this->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful()
        ->assertSee('Tomato Store');
});
