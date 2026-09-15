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
    use CheckBalance;
    use DeleteOrder;
    use FindOrder;
    use GenerateUUID;
    use GetShippingPrice;
    use HandleRequest;
    use InventoryCheck;
    use Logger;
    use Shipping;
    use StatusUpdate;
    use StoreOrder;
    use StoreWebOrder;
    use SyncCart;
    use SyncItems;
    use SyncMeta;
    use UpdateAccountMeta;
    use UpdateOrder;
    use ValidateOrder;

    private Order $order;

    public function __construct()
    {
        $this->order = new Order;
    }

    public function setOrder(Order $order): self
    {
        $this->order = $order;

        return $this;
    }
}
