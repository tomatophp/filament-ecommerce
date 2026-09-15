<?php

use TomatoPHP\FilamentEcommerce\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
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

it('shows the company on the order view page when the order has one', function () {
    $company = CompanyFactory::new()->create(['name' => 'Tomato Store']);
    $branch = BranchFactory::new()->for($company)->create();
    $order = OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);

    $this->get(OrderResource::getUrl('view', ['record' => $order]))
        ->assertSuccessful()
        ->assertSee('Tomato Store');
});
