### v5.0.1

- point `homepage` at the plugin page on tomatophp.com

### V5.0.0

- Support Filament v5 and Laravel 12 / 13
- Require the v5 lines of filament-cms, filament-accounts, filament-types, filament-locations and filament-settings-hub
- Translatable products use lara-zeus/spatie-translatable
- Order import/export use Filament's `ImportAction` / `ExportAction`
- Location foreign keys are only created when the filament-locations tables exist
- The bundled settings-hub, accounts and translatable plugins are only registered when the panel does not already have them, so the host app's configuration of those plugins is kept
- Model factories ship with the package (`TomatoPHP\FilamentEcommerce\Database\Factories`) and are resolved through `newFactory()`
- The order view page no longer fails for orders without a company, or for orders with items (the summary read a missing `tax` attribute instead of the item `vat`)
- Ships its own stylesheet (registered with `FilamentAsset`, published by `php artisan filament:assets`) so the order summary, status settings and state widget lay out correctly in Filament v5 panels
- Add a Pest test suite
