<?php

use TomatoPHP\FilamentEcommerce\Filament\Pages\InventorySettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderReceiptSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderStatusSettingsPage;
use TomatoPHP\FilamentEcommerce\Settings\OrderingSettings;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;
use TomatoPHP\FilamentTypes\Models\Type;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('serves the settings pages over http', function (string $page) {
    $this->get($page::getUrl())->assertSuccessful();
})->with([
    OrderSettingsPage::class,
    OrderStatusSettingsPage::class,
    OrderReceiptSettingsPage::class,
]);

it('saves the order settings', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();

    livewire(OrderSettingsPage::class)
        ->assertSuccessful()
        ->fillForm([
            'ordering_stating_code' => 'SHOP',
            'ordering_company_id' => $company->id,
            'ordering_web_branch' => $branch->id,
            'ordering_mobile_branch' => $branch->id,
            'ordering_direct_branch' => $branch->id,
            'ordering_active_shipping_fees' => true,
            'ordering_shipping_fees' => 25,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $settings = app(OrderingSettings::class);
    expect($settings->ordering_stating_code)->toBe('SHOP')
        ->and($settings->ordering_company_id)->toBe($company->id)
        ->and($settings->ordering_shipping_fees)->toEqual(25);
});

it('saves the receipt settings', function () {
    livewire(OrderReceiptSettingsPage::class)
        ->assertSuccessful()
        ->fillForm(['ordering_show_tax_number' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(OrderingSettings::class)->ordering_show_tax_number)->toBeFalse();
});

it('saves the inventory settings', function () {
    $branch = BranchFactory::new()->create();

    livewire(InventorySettingsPage::class)
        ->assertSuccessful()
        ->fillForm([
            'ordering_active_inventory' => true,
            'ordering_active_inventory_web_branch' => $branch->id,
            'ordering_active_inventory_direct_branch' => $branch->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect(app(OrderingSettings::class))
        ->ordering_active_inventory->toBeTrue()
        ->ordering_active_inventory_direct_branch->toBe($branch->id);
});

it('seeds and edits the order statuses', function () {
    livewire(OrderStatusSettingsPage::class)->assertSuccessful();

    $pending = Type::query()->where('for', 'orders')->where('type', 'status')->where('key', 'pending')->firstOrFail();
    expect(Type::query()->where('for', 'orders')->where('type', 'status')->count())->toBe(10);

    livewire(OrderStatusSettingsPage::class)
        ->callTableAction('edit', $pending, data: [
            'name' => ['en' => 'Waiting', 'ar' => 'انتظار'],
            'icon' => 'heroicon-o-clock',
            'color' => '#ff0000',
        ])
        ->assertHasNoTableActionErrors();

    expect($pending->refresh())
        ->color->toBe('#ff0000')
        ->and($pending->getTranslation('name', 'en'))->toBe('Waiting');
});
