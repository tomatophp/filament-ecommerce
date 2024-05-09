![Screenshot](https://github.com/tomatophp/filament-ecommerce/blob/master/arts/3x1io-tomato-ecommerce.jpg)

# Filament Ecommerce Builder

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-ecommerce/version.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![PHP Version Require](http://poser.pugx.org/tomatophp/filament-ecommerce/require/php)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![License](https://poser.pugx.org/tomatophp/filament-ecommerce/license.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![Downloads](https://poser.pugx.org/tomatophp/filament-ecommerce/d/total.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)


Build your own ecommerce store with Filament & Splade

## Installation

```bash
composer require tomatophp/filament-ecommerce
```
after install your package please run this command

```bash
php artisan filament-ecommerce:install
```

finally register the plugin on `/app/Providers/Filament/AdminPanelProvider.php`

```php
->plugin(\TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin::make())
```

## Publish Assets

you can publish config file by use this command

```bash
php artisan vendor:publish --tag="filament-ecommerce-config"
```

you can publish views file by use this command

```bash
php artisan vendor:publish --tag="filament-ecommerce-views"
```

you can publish languages file by use this command

```bash
php artisan vendor:publish --tag="filament-ecommerce-lang"
```

you can publish migrations file by use this command

```bash
php artisan vendor:publish --tag="filament-ecommerce-migrations"
```

## Support

you can join our discord server to get support [TomatoPHP](https://discord.gg/Xqmt35Uh)

## Docs

you can check docs of this package on [Docs](https://docs.tomatophp.com/plugins/laravel-package-generator)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Tomatophp](mailto:info@3x1.io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
