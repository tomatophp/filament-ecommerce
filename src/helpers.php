<?php


if(!function_exists('wishlist')){
    function wishlist(int $product_id): bool
    {
        if(auth('accounts')->user()){
            $wishlist = \TomatoPHP\FilamentEcommerce\Models\Wishlist::where('account_id', auth('accounts')->user()->id)
                ->where('product_id', $product_id)->first();

            if($wishlist){
                return true;
            }
        }

        return false;
    }
}
