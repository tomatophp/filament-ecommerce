### V5.0.0

- Support Filament v5 and Laravel 12 / 13
- Require the v5 lines of filament-cms, filament-accounts, filament-types, filament-locations and filament-settings-hub
- Translatable products use lara-zeus/spatie-translatable
- Order import/export use Filament's `ImportAction` / `ExportAction`
- Location foreign keys are only created when the filament-locations tables exist
- The bundled settings-hub, accounts and translatable plugins are only registered when the panel does not already have them, so the host app's configuration of those plugins is kept
- Add a Pest test suite
