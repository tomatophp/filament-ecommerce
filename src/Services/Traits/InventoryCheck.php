<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

trait InventoryCheck
{
    /**
     * Uses tomatophp/tomato-inventory when it is installed; otherwise every item is available.
     */
    public function checkInventory(array $items): string
    {
        $inventory = 'TomatoPHP\\TomatoInventory\\Facades\\TomatoInventory';
        if (! class_exists($inventory)) {
            return 'success';
        }

        foreach ($items as $item) {
            $checkQTY = $inventory::checkBranchInventory(
                productID: $item['item']['id'],
                branchID: setting('ordering_active_inventory_direct_branch'),
                qty: $item['qty'],
                options: $item['options'] ?? []
            );

            if (! $checkQTY) {
                return $item['item']['sku'];
            }
        }

        return 'success';
    }
}
