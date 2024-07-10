<?php

namespace TomatoPHP\FilamentEcommerce\Services;

use TomatoPHP\FilamentCms\Models\Category;
use TomatoPHP\FilamentEcommerce\Models\Coupon;
use TomatoPHP\FilamentEcommerce\Models\Order;
use TomatoPHP\FilamentEcommerce\Models\Product;

class Coupons
{
    private Coupon $coupon;
    private array $products = [];

    public function products(array $productsIds): static
    {
        $this->products = $productsIds;
        return $this;
    }

    public function check(string $code, ?Order $order = null): bool
    {
        $coupon = \TomatoPHP\FilamentEcommerce\Models\Coupon::where('code', $code)->first();
        if (!$coupon) {
            return false;
        }

        $this->coupon = $coupon;

        if (!$this->isActive()) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        if($this->coupon->is_limited){
            if ($this->isUsed()) {
                return false;
            }

            $products = $order ? $order->ordersItems()->pluck('product_id')->toArray() : $this->products;
            if($this->checkItems($products)){
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
        if($this->coupon->use_limit <= $this->coupon->is_used){
            return true;
        }

        return false;
    }

    private function isUsedByUser(): bool
    {
        if($this->coupon->use_limit <= $this->coupon->is_used){
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

    public function discount(string $code, ?Order $order = null, ?float $total=0): float
    {
        $check = $this->check($code, $order);
        if($check){
            $total = $order ? $order->total : $total;
            $amount = 0;
            if ($this->coupon->type == 'discount_coupon') {
                $amount = $this->coupon->amount;
            } elseif ($this->coupon->type == 'percentage_coupon') {
                $amount = $total * ($this->coupon->amount / 100);
            }

            return $amount;
        }
        else {
            return 0;
        }
    }

    private function checkItems(array $products)
    {
        $apply = collect($this->coupon->apply_to);
        $except = collect($this->coupon->except);


        if($apply->count() > 0 || $except->count() > 0){
            $productIds = [];
            $categoryIds = [];
            $getApply = false;
            $getApplyCount = $apply->count() > 0;
            $getExcept = false;
            $getExceptCount = $except->count() > 0;

            foreach ($apply as $applyItem){
                foreach ($products as $product){
                    $getProduct = Product::find($product);
                    if($getProduct){
                        if(
                            ($applyItem['model_type'] === Product::class && $applyItem['model_id'] == $getProduct->id) ||
                            ($applyItem['model_type'] === Category::class && $applyItem['model_id'] == $getProduct->category_id) ||
                            ($applyItem['model_type'] === Category::class &&  $getProduct->categories()->where('category_id', $applyItem['model_id'])->first())
                        )
                        {
                            $getApply = true;
                        }
                        else {
                            $getApply = false;
                        }
                    }
                }
            }

            foreach ($except as $exceptItem){
                foreach ($products as $product){
                    $getProduct = Product::find($product);
                    if($getProduct){
                        if(
                            ($exceptItem['model_type'] === Product::class && $exceptItem['model_id'] == $getProduct->id) ||
                            ($exceptItem['model_type'] === Category::class && $exceptItem['model_id'] == $getProduct->category_id) ||
                            ($exceptItem['model_type'] === Category::class &&  $getProduct->categories()->where('category_id', $exceptItem['model_id'])->first())
                        )
                        {
                            $getExcept = true;
                        }
                        else {
                            $getExcept = false;
                        }
                    }
                }
            }
        }

        $finalApply = match (true){
            $getApply && $getExcept && $getExceptCount && $getApplyCount => true,
            $getApply && !$getExcept => false,
            !$getApply && $getExcept => true,
            !$getApply && $getApplyCount => true,
            !$getExcept && $getExceptCount => false,
            default => false
        };

        return $finalApply;
    }
}
