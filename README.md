![Screenshot](https://raw.githubusercontent.com/tomatophp/filament-ecommerce/master/arts/3x1io-tomato-ecommerce.jpg)

# Filament Ecommerce Builder

[![Latest Stable Version](https://poser.pugx.org/tomatophp/filament-ecommerce/version.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![License](https://poser.pugx.org/tomatophp/filament-ecommerce/license.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)
[![Downloads](https://poser.pugx.org/tomatophp/filament-ecommerce/d/total.svg)](https://packagist.org/packages/tomatophp/filament-ecommerce)


Build your own ecommerce store with FilamentPHP with the Power of Tomato CMS Builder

## Installation

```bash
composer require tomatophp/filament-ecommerce
```

we need the Media Library plugin to be installed and migrated you can use this command to publish the migration

```bash
php artisan vendor:publish --provider="Spatie\MediaLibrary\MediaLibraryServiceProvider" --tag="medialibrary-migrations"
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
->plugin(
    \TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin::make()
        ->useCoupon()
        ->useGiftCard()
        ->useReferralCode()
        ->allowOrderExport()
        ->allowOrderImport()
        ->useWidgets()
)
```

there is some feature you can disable it

```php
->plugin(
    \TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin::make()
        ->useAccounts(false)
        ->useOrderSettings(false)
        ->useSettings(false)
        ->showOrderAccount(false)
        ->allowOrderCreate(false)
)
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


## Use Filament Shield

you can use the shield to protect your resource and allow user roles by install it first

```bash
composer require bezhansalleh/filament-shield
```

Add the Spatie\Permission\Traits\HasRoles trait to your User model(s):

```php
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;

    // ...
}
```
Publish the config file then setup your configuration:

```php
->plugin(\BezhanSalleh\FilamentShield\FilamentShieldPlugin::make())
```

Now run the following command to install shield:

```bash
php artisan shield:install
```

Now we can [publish the package assets]([https://github.com/bezhanSalleh/filament-shield](https://github.com/tomatophp/filament-users?tab=readme-ov-file#publish-assets)).

```bash
php artisan vendor:publish --tag="filament-users-config"
```

now you need to allow it on the plugin options

```php
->plugin(\TomatoPHP\FilamentEcommerce\FilamentEcommercePlugin::make()->allowShield())
```

for more information check the [Filament Shield](https://github.com/bezhanSalleh/filament-shield)

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

## Other Filament Packages

Checkout our [Awesome TomatoPHP](https://github.com/tomatophp/awesome)
