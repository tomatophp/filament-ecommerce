<?php

namespace TomatoPHP\FilamentEcommerce\Services;

use TomatoPHP\FilamentEcommerce\Models\Coupon;

class Coupons
{
    private Coupon $coupon;
    public function check(string $code): bool
    {
        $coupon = \TomatoPHP\FilamentEcommerce\Models\Coupon::where('code', $code)->first();
        if (!$coupon) {
            return false;
        }

        $this->coupon = $coupon;

        if ($this->isActive()) {
            return false;
        }

        if($this->coupon->is_limited){
            if ($this->isExpired()) {
                return false;
            }

            if ($this->isUsed()) {
                return false;
            }
        }

        return true;
    }

    private function isExpired(): bool
    {
        if ($this->coupon->end_at) {
            if (now()->gt($this->coupon->end_at)) {
                return true;
            }
        }

        return false;
    }


    private function isUsed(): bool
    {
        if($this->coupon->use_limit >= $this->coupon->is_used){
            return true;
        }

        return false;
    }

    private function isUsedByUser(): bool
    {
        if($this->coupon->use_limit >= $this->coupon->is_used){
            return true;
        }

        return false;
    }

    private function isActive(): bool
    {
        if ($this->coupon->is_activated) {
            return true;
        }

        return false;
    }

    private function getDiscountAmount(float $total): float
    {
        $amount = 0;
        if ($this->coupon->type == 'discount_coupon') {
            $amount = $this->coupon->amount;
        } elseif ($this->coupon->type == 'percentage_coupon') {
            $amount = $total * ($this->coupon->amount / 100);
        }

        return $amount;
    }



    public function apply(){

    }

}
