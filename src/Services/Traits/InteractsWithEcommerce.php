<?php

namespace TomatoPHP\FilamentEcommerce\Services\Traits;

use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Models\Wishlist;
use TomatoPHP\TomatoProducts\Models\ProductReview;

trait InteractsWithEcommerce
{
    public function reviews(){
        return $this->hasMany(ProductReview::class);
    }

    public function wishlist(){
        return $this->hasMany(Wishlist::class);
    }

    public function carts(){
        return $this->hasMany(Cart::class);
    }
}
