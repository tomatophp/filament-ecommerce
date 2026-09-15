<?php

use Filament\Panel;
use LaraZeus\SpatieTranslatable\SpatieTranslatablePlugin;
use TomatoPHP\FilamentAccounts\FilamentAccountsPlugin;
use TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin;
use TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin;

/**
 * The ecommerce plugin bundles settings-hub, accounts and translatable. A host panel that already
 * registered and configured one of them must keep its own instance and options.
 */
it('keeps the host panel configuration of plugins it bundles', function () {
    $settingsHub = FilamentSettingsHubPlugin::make();
    $accounts = FilamentAccountsPlugin::make();
    $translatable = SpatieTranslatablePlugin::make()->defaultLocales(['en', 'ar']);

    $panel = Panel::make()
        ->id('bundled-plugins-host')
        ->plugin($settingsHub)
        ->plugin($accounts)
        ->plugin($translatable)
        ->plugin(FilamentEcommercePlugin::make());

    expect($panel->getPlugin('filament-settings-hub'))->toBe($settingsHub)
        ->and($panel->getPlugin('filament-accounts'))->toBe($accounts)
        ->and($panel->getPlugin('spatie-translatable'))->toBe($translatable);
});

it('registers the bundled plugins when the panel does not have them', function () {
    $panel = Panel::make()
        ->id('bundled-plugins-empty')
        ->plugin(FilamentEcommercePlugin::make());

    expect($panel->hasPlugin('filament-settings-hub'))->toBeTrue()
        ->and($panel->hasPlugin('filament-accounts'))->toBeTrue()
        ->and($panel->hasPlugin('spatie-translatable'))->toBeTrue();
});
