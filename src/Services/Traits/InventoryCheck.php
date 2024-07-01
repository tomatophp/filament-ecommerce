<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use Illuminate\Http\Request;
use TomatoPHP\TomatoInventory\Facades\TomatoInventory;

trait InventoryCheck
{
    public function checkInventory(array $items): string
    {
        foreach ($items as $item){
            $checkQTY = TomatoInventory::checkBranchInventory(
                productID: $item['item']['id'],
                branchID: setting('ordering_active_inventory_direct_branch'),
                qty: $item['qty'],
                options: $item['options'] ?? []
            );

            if(!$checkQTY){
                return $item['item']['sku'];
            }
        }

        return "success";
    }
}
