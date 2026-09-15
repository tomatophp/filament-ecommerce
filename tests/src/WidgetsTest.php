<?php

use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderPaymentMethodChart;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderSourceChart;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrdersStateWidget;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderStateChart;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();
    OrderFactory::new()->count(2)->create();
    OrderFactory::new()->create(['status' => 'shipped']);
});

it('renders the widgets', function (string $widget) {
    livewire($widget)->assertSuccessful();
})->with([
    OrdersStateWidget::class,
    OrderPaymentMethodChart::class,
    OrderSourceChart::class,
    OrderStateChart::class,
]);

it('shows the order count per status', function () {
    livewire(OrdersStateWidget::class)
        ->assertSuccessful()
        ->assertSee('Pending');
});

it('adds the widgets to the dashboard', function () {
    $this->get('/admin')->assertSuccessful();
});
