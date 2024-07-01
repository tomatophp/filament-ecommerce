<?php

namespace TomatoPHP\FilamentEcommerce\Services;

use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Services\Traits\CheckBalance;
use TomatoPHP\FilamentEcommerce\Services\Traits\DeleteOrder;
use TomatoPHP\FilamentEcommerce\Services\Traits\FindOrder;
use TomatoPHP\FilamentEcommerce\Services\Traits\GenerateUUID;
use TomatoPHP\FilamentEcommerce\Services\Traits\GetShippingPrice;
use TomatoPHP\FilamentEcommerce\Services\Traits\HandleRequest;
use TomatoPHP\FilamentEcommerce\Services\Traits\InventoryCheck;
use TomatoPHP\FilamentEcommerce\Services\Traits\Logger;
use TomatoPHP\FilamentEcommerce\Services\Traits\Shipping;
use TomatoPHP\FilamentEcommerce\Services\Traits\StatusUpdate;
use TomatoPHP\FilamentEcommerce\Services\Traits\StoreOrder;
use TomatoPHP\FilamentEcommerce\Services\Traits\StoreWebOrder;
use TomatoPHP\FilamentEcommerce\Services\Traits\SyncCart;
use TomatoPHP\FilamentEcommerce\Services\Traits\SyncItems;
use TomatoPHP\FilamentEcommerce\Services\Traits\SyncMeta;
use TomatoPHP\FilamentEcommerce\Services\Traits\UpdateAccountMeta;
use TomatoPHP\FilamentEcommerce\Services\Traits\UpdateOrder;
use TomatoPHP\FilamentEcommerce\Services\Traits\ValidateOrder;

class Ordering
{
    use GenerateUUID;
    use Logger;
    use HandleRequest;
    use ValidateOrder;
    use InventoryCheck;
    use SyncCart;
    use SyncItems;
    use SyncMeta;
    use StoreOrder;
    use UpdateAccountMeta;
    use GetShippingPrice;
    use CheckBalance;
    use StoreWebOrder;
    use Shipping;
    use UpdateOrder;
    use StatusUpdate;
    use DeleteOrder;
    use FindOrder;

    private Order $order;

    public function __construct()
    {
        $this->order = new Order();
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;
        return $this;
    }
}
