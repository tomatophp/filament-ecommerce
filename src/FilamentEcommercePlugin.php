<?php

namespace TomatoPHP\FilamentEcommerce;

use Filament\SpatieLaravelTranslatablePlugin;
use Nwidart\Modules\Module;
use TomatoPHP\FilamentAccounts\FilamentAccountsPlugin;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderReceiptSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Pages\OrderStatusSettingsPage;
use TomatoPHP\FilamentEcommerce\Filament\Resources\BranchResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\DeliveryResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource;
use Filament\Contracts\Plugin;
use Filament\Panel;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderPaymentMethodChart;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderSourceChart;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrdersStateWidget;
use TomatoPHP\FilamentEcommerce\Filament\Widgets\OrderStateChart;
use TomatoPHP\FilamentEcommerce\Models\GiftCard;
use TomatoPHP\FilamentEcommerce\Models\ShippingVendor;
use TomatoPHP\FilamentLocations\FilamentLocationsPlugin;
use TomatoPHP\FilamentSettingsHub\Facades\FilamentSettingsHub;
use TomatoPHP\FilamentSettingsHub\FilamentSettingsHubPlugin;
use TomatoPHP\FilamentSettingsHub\Services\Contracts\SettingHold;
use TomatoPHP\FilamentTypes\FilamentTypesPlugin;


class FilamentEcommercePlugin implements Plugin
{
    private bool $isActive = false;

    public function getId(): string
    {
        return 'filament-ecommerce';
    }

    public static bool $useAccounts = true;
    public static bool $useSettings = true;
    public static bool $useCompany = true;
    public static bool $useProduct = true;
    public static bool $useOrder = true;
    public static bool $useOrderSettings = true;
    public static bool $allowOrderCreate = true;
    public static bool $allowOrderExport = false;
    public static bool $allowOrderImport = false;
    public static bool $showOrderAccount = true;
    public static bool $useShippingVendor = true;
    public static bool $useCoupon = false;
    public static bool $useGiftCard = false;
    public static bool $useReferralCode = false;
    public static ?bool $useWidgets = false;
    public static ?array $locals = ['en', 'ar'];

    public function useAccounts(bool $condition = true): static
    {
        self::$useAccounts = $condition;
        return $this;
    }

    public function useSettings(bool $condition = true): static
    {
        self::$useSettings = $condition;
        return $this;
    }

    public function useCompany(bool $condition = true): static
    {
        self::$useCompany = $condition;
        return $this;
    }

    public function useProduct(bool $condition = true): static
    {
        self::$useProduct = $condition;
        return $this;
    }

    public function useOrder(bool $condition = true): static
    {
        self::$useOrder = $condition;
        return $this;
    }

    public function useOrderSettings(bool $condition = true): static
    {
        self::$useOrderSettings = $condition;
        return $this;
    }

    public function allowOrderCreate(bool $condition = true): static
    {
        self::$allowOrderCreate = $condition;
        return $this;
    }

    public function allowOrderExport(bool $condition = true): static
    {
        self::$allowOrderExport = $condition;
        return $this;
    }

    public function allowOrderImport(bool $condition = true): static
    {
        self::$allowOrderImport = $condition;
        return $this;
    }

    public function showOrderAccount(bool $condition = true): static
    {
        self::$showOrderAccount = $condition;
        return $this;
    }

    public function useShippingVendor(bool $condition = true): static
    {
        self::$useShippingVendor = $condition;
        return $this;
    }

    public function useCoupon(bool $condition = true): static
    {
        self::$useCoupon = $condition;
        return $this;
    }

    public function useGiftCard(bool $condition = true): static
    {
        self::$useGiftCard = $condition;
        return $this;
    }

    public function useReferralCode(bool $condition = true): static
    {
        self::$useReferralCode = $condition;
        return $this;
    }

    public function useWidgets(bool $condation = true): static
    {
        self::$useWidgets = $condation;
        return $this;
    }

    public function defaultLocales(array $locales): static
    {
        self::$locals = $locales;
        return $this;
    }

    public function register(Panel $panel): void
    {
        if (class_exists(Module::class) && \Nwidart\Modules\Facades\Module::find('FilamentEcommerce')?->isEnabled()) {
            $this->isActive = true;
        } else {
            $this->isActive = true;
        }

        if ($this->isActive) {
            $panel
                ->plugins(self::$useSettings? [
                    FilamentSettingsHubPlugin::make()
                ] : [])
                ->plugins(self::$useAccounts ? [
                    FilamentAccountsPlugin::make()
                        ->canLogin()
                        ->canBlocked()
                ] : [])
                ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales(self::$locals))
                ->resources(array_filter([
                    self::$useCompany ? CompanyResource::class : null,
                    self::$useProduct ? ProductResource::class : null,
                    self::$useOrder ? OrderResource::class : null,
                    self::$useShippingVendor ? ShippingVendorResource::class : null,
                    self::$useCoupon ? CouponResource::class : null,
                    self::$useGiftCard ? GiftCardResource::class : null,
                    self::$useReferralCode ? ReferralCodeResource::class : null,
                ]))
                ->widgets(self::$useWidgets ? [
                    OrdersStateWidget::class,
                    OrderPaymentMethodChart::class,
                    OrderSourceChart::class,
                    OrderStateChart::class
                ] : [])
                ->pages(self::$useOrder ? [
                    OrderSettingsPage::class,
                    OrderStatusSettingsPage::class,
                    OrderReceiptSettingsPage::class
                ] : []);
        }
    }

    public function boot(Panel $panel): void {

        if(self::$useSettings){
            FilamentSettingsHub::register([
                SettingHold::make()
                    ->label('filament-ecommerce::messages.settings.orders.title')
                    ->icon('heroicon-o-building-storefront')
                    ->route(OrderSettingsPage::getRouteName())
                    ->description('filament-ecommerce::messages.settings.orders.description')
                    ->group('filament-ecommerce::messages.settings.group'),
                SettingHold::make()
                    ->label('filament-ecommerce::messages.settings.status.title')
                    ->icon('heroicon-o-check-circle')
                    ->route(OrderStatusSettingsPage::getRouteName())
                    ->description('filament-ecommerce::messages.settings.status.description')
                    ->group('filament-ecommerce::messages.settings.group'),
                SettingHold::make()
                    ->label('filament-ecommerce::messages.settings.receipt.title')
                    ->icon('heroicon-o-printer')
                    ->route(OrderReceiptSettingsPage::getRouteName())
                    ->description('filament-ecommerce::messages.settings.receipt.description')
                    ->group('filament-ecommerce::messages.settings.group'),
            ]);
        }

    }

    public static function make(): static
    {
        return new static();
    }
}
