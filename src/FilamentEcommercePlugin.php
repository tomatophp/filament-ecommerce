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

    public bool $useAccounts = false;
    public bool $useSettings = false;
    public bool $useCompany = false;
    public bool $useProduct = false;
    public bool $useOrder = false;
    public bool $useOrderSettings = false;
    public bool $allowOrderCreate = false;
    public bool $allowOrderExport = false;
    public bool $allowOrderImport = false;
    public bool $showOrderAccount = false;
    public bool $useShippingVendor = false;
    public bool $useCoupon = false;
    public bool $useGiftCard = false;
    public bool $useReferralCode = false;
    public ?bool $useWidgets = false;
    public ?array $locals = ['en', 'ar'];

    public function useAccounts(bool $condition = true): static
    {
        $this->useAccounts = $condition;
        return $this;
    }

    public function useSettings(bool $condition = true): static
    {
        $this->useSettings = $condition;
        return $this;
    }

    public function useCompany(bool $condition = true): static
    {
        $this->useCompany = $condition;
        return $this;
    }

    public function useProduct(bool $condition = true): static
    {
        $this->useProduct = $condition;
        return $this;
    }

    public function useOrder(bool $condition = true): static
    {
        $this->useOrder = $condition;
        return $this;
    }

    public function useOrderSettings(bool $condition = true): static
    {
        $this->useOrderSettings = $condition;
        return $this;
    }

    public function allowOrderCreate(bool $condition = true): static
    {
        $this->allowOrderCreate = $condition;
        return $this;
    }

    public function allowOrderExport(bool $condition = true): static
    {
        $this->allowOrderExport = $condition;
        return $this;
    }

    public function allowOrderImport(bool $condition = true): static
    {
        $this->allowOrderImport = $condition;
        return $this;
    }

    public function showOrderAccount(bool $condition = true): static
    {
        $this->showOrderAccount = $condition;
        return $this;
    }

    public function useShippingVendor(bool $condition = true): static
    {
        $this->useShippingVendor = $condition;
        return $this;
    }

    public function useCoupon(bool $condition = true): static
    {
        $this->useCoupon = $condition;
        return $this;
    }

    public function useGiftCard(bool $condition = true): static
    {
        $this->useGiftCard = $condition;
        return $this;
    }

    public function useReferralCode(bool $condition = true): static
    {
        $this->useReferralCode = $condition;
        return $this;
    }

    public function useWidgets(bool $condation = true): static
    {
        $this->useWidgets = $condation;
        return $this;
    }

    public function defaultLocales(array $locales): static
    {
        $this->locals = $locales;
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
                ->plugins($this->useSettings ? [
                    FilamentSettingsHubPlugin::make()
                ] : [])
                ->plugins($this->useAccounts ? [
                    FilamentAccountsPlugin::make()
                        ->canLogin()
                        ->canBlocked()
                ] : [])
                ->plugin(SpatieLaravelTranslatablePlugin::make()->defaultLocales($this->locals))
                ->resources(array_filter([
                    $this->useCompany ? CompanyResource::class : null,
                    $this->useProduct ? ProductResource::class : null,
                    $this->useOrder ? OrderResource::class : null,
                    $this->useShippingVendor ? ShippingVendorResource::class : null,
                    $this->useCoupon ? CouponResource::class : null,
                    $this->useGiftCard ? GiftCardResource::class : null,
                    $this->useReferralCode ? ReferralCodeResource::class : null,
                ]))
                ->widgets($this->useWidgets ? [
                    OrdersStateWidget::class,
                    OrderPaymentMethodChart::class,
                    OrderSourceChart::class,
                    OrderStateChart::class
                ] : [])
                ->pages($this->useOrder ? [
                    OrderSettingsPage::class,
                    OrderStatusSettingsPage::class,
                    OrderReceiptSettingsPage::class
                ] : []);
        }
    }

    public function boot(Panel $panel): void {}

    public static function make(): static
    {
        return new static();
    }
}
