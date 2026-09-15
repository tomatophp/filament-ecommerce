<?php

use TomatoPHP\FilamentEcommerce\Database\Factories\AccountFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\CouponFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Database\Factories\ShippingVendorFactory;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CouponResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\GiftCardResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ReferralCodeResource;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource;
use TomatoPHP\FilamentEcommerce\Models\GiftCard;
use TomatoPHP\FilamentEcommerce\Models\ReferralCode;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();
});

it('serves every resource index over http', function (string $resource) {
    $this->get($resource::getUrl('index'))->assertSuccessful();
})->with([
    CompanyResource::class,
    ProductResource::class,
    OrderResource::class,
    ShippingVendorResource::class,
    CouponResource::class,
    GiftCardResource::class,
    ReferralCodeResource::class,
]);

it('renders the list pages with records', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();
    ProductFactory::new()->count(2)->create();
    OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);
    ShippingVendorFactory::new()->create();
    CouponFactory::new()->create();

    livewire(CompanyResource\Pages\ListCompanies::class)->assertSuccessful()->assertSee($company->name);
    livewire(ProductResource\Pages\ListProducts::class)->assertSuccessful();
    livewire(OrderResource\Pages\ListOrders::class)->assertSuccessful();
    livewire(ShippingVendorResource\Pages\ListShippingVendors::class)->assertSuccessful();
    livewire(CouponResource\Pages\ListCoupons::class)->assertSuccessful();
    livewire(GiftCardResource\Pages\ListGiftCards::class)->assertSuccessful();
    livewire(ReferralCodeResource\Pages\ListReferralCodes::class)->assertSuccessful();
});

it('renders the create pages', function (string $page) {
    livewire($page)->assertSuccessful();
})->with([
    CompanyResource\Pages\CreateCompany::class,
    ProductResource\Pages\CreateProduct::class,
    OrderResource\Pages\CreateOrder::class,
    ShippingVendorResource\Pages\CreateShippingVendor::class,
    CouponResource\Pages\CreateCoupon::class,
    GiftCardResource\Pages\CreateGiftCard::class,
    ReferralCodeResource\Pages\CreateReferralCode::class,
]);

it('renders the edit pages', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();
    $account = AccountFactory::new()->create();

    livewire(CompanyResource\Pages\EditCompany::class, ['record' => $company->getRouteKey()])->assertSuccessful();
    livewire(ProductResource\Pages\EditProduct::class, ['record' => ProductFactory::new()->create()->getRouteKey()])->assertSuccessful();
    livewire(OrderResource\Pages\EditOrder::class, [
        'record' => OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id])->getRouteKey(),
    ])->assertSuccessful();
    livewire(ShippingVendorResource\Pages\EditShippingVendor::class, ['record' => ShippingVendorFactory::new()->create()->getRouteKey()])->assertSuccessful();
    livewire(CouponResource\Pages\EditCoupon::class, ['record' => CouponFactory::new()->create()->getRouteKey()])->assertSuccessful();
    livewire(GiftCardResource\Pages\EditGiftCard::class, [
        'record' => GiftCard::query()->create(['account_id' => $account->id, 'name' => 'Gift', 'code' => 'GIFT-1', 'balance' => 50])->getRouteKey(),
    ])->assertSuccessful();
    livewire(ReferralCodeResource\Pages\EditReferralCode::class, [
        'record' => ReferralCode::query()->create(['account_id' => $account->id, 'name' => 'Friends', 'code' => 'REF-1'])->getRouteKey(),
    ])->assertSuccessful();
});

it('renders the order view page', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();
    $order = OrderFactory::new()->create(['company_id' => $company->id, 'branch_id' => $branch->id]);

    livewire(OrderResource\Pages\ViewOrder::class, ['record' => $order->getRouteKey()])
        ->assertSuccessful()
        ->assertSee($order->uuid);

    $this->get(OrderResource::getUrl('view', ['record' => $order]))->assertSuccessful();
});
