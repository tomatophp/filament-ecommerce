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

## Features

- [x] Multi Company / Branches Management
- [x] Product Management
- [x] Product Table Group By Type
- [x] Product Multi Variants
- [x] Product Multi Images
- [x] Product Multi Languages Content
- [x] Shipping Vendor Management
- [x] Order Management
- [x] Order Shipping Action
- [x] Order Change Status Action
- [x] Order Table Group By Status
- [x] Order Receipt Print
- [x] Order Table Summary
- [ ] Order Settings
- [ ] Order PDF Export
- [ ] Order Export
- [ ] Order Import
- [ ] Product Import
- [ ] Product Export
- [ ] Product Variants On Order
- [ ] Coupon Management
- [ ] Gift Card Management
- [ ] Refferal Management
- [ ] Pay Order With Wallet
- [ ] Pay Order With Gift Card
- [ ] Add Discount To Order Using Coupon
- [ ] Cart Manager
- [ ] Comparison Between Products
- [ ] Wishlist Manager
- [ ] Product Download for Digital Products
- [ ] Search History Manager
- [ ] Filament CMS Page Builder Integration
- [ ] Filament CMS Themes Integration
- [ ] Reports Widgets
- [ ] Order Facade Methods
- [ ] Product Service Class
- [ ] Coupons Service Class
- [ ] Ecommerce Service Class
- [ ] Ecommerce APIs

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

you can check docs of this package on [Docs](https://docs.tomatophp.com/filament/filament-ecommerce)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

Please see [SECURITY](SECURITY.md) for more information about security.

## Credits

- [Fady Mondy](https://wa.me/+201207860084)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
