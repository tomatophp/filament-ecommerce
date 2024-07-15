![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/3x1io-tomato-ecommerce.jpg)

# Filament Ecommerce Builder

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-ecommerce/version.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![PHP Version Require](http://poser.pugx.org/tomatophp/filament-ecommerce/require/php)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![License](https://poser.pugx.org/tomatophp/filament-ecommerce/license.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![Downloads](https://poser.pugx.org/tomatophp/filament-ecommerce/d/total.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)


Build your own ecommerce store with FilamentPHP with the Power of Tomato CMS Builder

## Installation

```bash
composer require tomatophp/filament-ecommerce
```

now you need to install the settings hub use these commands

```bash
php artisan vendor:publish --provider="Spatie\LaravelSettings\LaravelSettingsServiceProvider" --tag="migrations"
php artisan filament-settings-hub:install
```

then you need to publish the account model

```bash
php artisan vendor:publish --tag="filament-accounts-model"
```

after installing your package please run this command

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
- [x] Order Logs
- [x] Order Shipping Action
- [x] Order Change Status Action
- [x] Order Table Group By Status
- [x] Order Receipt Print
- [x] Order Table Summary
- [x] Order Settings
- [x] Order Export
- [x] Order Import
- [x] Order Charts Widgets
- [x] Coupon Management
- [x] Gift Card Management
- [x] Refferal Management
- [x] Coupons Service Class
- [x] Add Discount To Order Using Coupon
- [ ] Product Variants On Order
- [ ] Gitf Card Services
- [ ] Gift Card Apply To Account Wallet
- [ ] Pay Order With Wallet
- [ ] Pay Order With Gift Card
- [ ] Refferal Code Services
- [ ] Refferal Code Middleware Counter
- [ ] Order PDF Export
- [ ] Order Facade Methods
- [ ] Order Tracking Page
- [ ] Product Import
- [ ] Product Export
- [ ] Product Clone
- [ ] Cart Manager
- [ ] Comparison Between Products
- [ ] Wishlist Manager
- [ ] Product Download for Digital Products
- [ ] Search History Manager
- [ ] Filament CMS Page Builder Integration
- [ ] Filament CMS Themes Integration
- [ ] Product Service Class
- [ ] Ecommerce Service Class
- [ ] Ecommerce APIs

## Screenshots

![Branches](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/branches.png)
![Companies](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/companies.png)
![Edit Company](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/edit-company.png)
![Products List](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/products.png)
![Create Product](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/create-products.png)
![Create Order](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/create-orders.png)
![Order Items](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/order-items.png)
![Orders List](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/orders.png)
![Deliveries](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/deliveries.png)
![Edit Shipping](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/edit-shipping-vendors.png)
![Shipping](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/shipping-vendors.png)

## Use Coupon Service

you can use coupon service to check if coupon is valid or not

you can use this method to check for selected Order

```php
use \TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;

FilamentEcommerce::coupon()->check('coupon_code', \TomatoPHP\FilamentEcommerce\Models\Order::find(1));
```

or you can check the code for selected products

```php
use \TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;

FilamentEcommerce::coupon()->products([1,2,4])->check('coupon_code');
```

or you can get the direct discount amount of the code 

```php
use \TomatoPHP\FilamentEcommerce\Facades\FilamentEcommerce;

FilamentEcommerce::coupon()->products([1,2,4])->discount('coupon_code');
```

and it's the same as check you can apply to selected order or selected products.

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
