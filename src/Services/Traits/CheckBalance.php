<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

trait CheckBalance
{
    public function checkBalance(float $total): bool
    {
        $balance = auth('accounts')->user()->balance;
        if($balance >= $total){
            return true;
        }

        return false;
    }
}
