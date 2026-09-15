<?php

use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\Pages\EditCompany;
use TomatoPHP\FilamentEcommerce\Filament\Resources\CompanyResource\RelationManagers\CompanyBranches;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\Pages\ViewOrder;
use TomatoPHP\FilamentEcommerce\Filament\Resources\OrderResource\RelationManagers\OrderLog;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\Pages\EditProduct;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers\CodesManager;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ProductResource\RelationManagers\ProductReviewManager;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\Pages\EditShippingVendor;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers\ShippingDeliveryBoys;
use TomatoPHP\FilamentEcommerce\Filament\Resources\ShippingVendorResource\RelationManagers\ShippingVendorPrices;
use TomatoPHP\FilamentEcommerce\Models\ProductReview;
use TomatoPHP\FilamentEcommerce\Models\ShippingPrice;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\AccountFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\BranchFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\CompanyFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\DeliveryFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\OrderFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\ProductFactory;
use TomatoPHP\FilamentEcommerce\Tests\Database\Factories\ShippingVendorFactory;
use TomatoPHP\FilamentEcommerce\Tests\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Livewire\livewire;

beforeEach(function () {
    actingAs(User::factory()->create());
    seedOrderTypes();
});

it('renders the company branches', function () {
    $company = CompanyFactory::new()->create();
    $branch = BranchFactory::new()->for($company)->create();

    livewire(CompanyBranches::class, ['ownerRecord' => $company, 'pageClass' => EditCompany::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$branch]);
});

it('renders the order logs', function () {
    $order = OrderFactory::new()->create();
    $log = $order->orderLogs()->create(['status' => 'pending', 'note' => 'Order created', 'user_id' => auth()->id()]);

    livewire(OrderLog::class, ['ownerRecord' => $order, 'pageClass' => ViewOrder::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$log]);
});

it('renders the product reviews and codes', function () {
    $product = ProductFactory::new()->create();
    $review = ProductReview::query()->create([
        'product_id' => $product->id,
        'account_id' => AccountFactory::new()->create()->id,
        'rate' => 5,
        'review' => 'Fresh',
    ]);
    $code = $product->codes()->create(['code' => 'CODE-1']);

    livewire(ProductReviewManager::class, ['ownerRecord' => $product, 'pageClass' => EditProduct::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$review]);

    livewire(CodesManager::class, ['ownerRecord' => $product, 'pageClass' => EditProduct::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$code]);
});

it('renders the shipping vendor delivery boys and prices', function () {
    $vendor = ShippingVendorFactory::new()->create();
    $delivery = DeliveryFactory::new()->for($vendor, 'vendor')->create();
    $price = ShippingPrice::query()->create(['shipping_vendor_id' => $vendor->id, 'delivery_id' => $delivery->id, 'price' => 20]);

    livewire(ShippingDeliveryBoys::class, ['ownerRecord' => $vendor, 'pageClass' => EditShippingVendor::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$delivery]);

    livewire(ShippingVendorPrices::class, ['ownerRecord' => $vendor, 'pageClass' => EditShippingVendor::class])
        ->assertSuccessful()
        ->assertCanSeeTableRecords([$price]);
});
