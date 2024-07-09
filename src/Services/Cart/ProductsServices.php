<?php

namespace TomatoPHP\FilamentEcommerce\Services\Cart;

use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Services\Cart\Contracts\ProductPriceModel;
use TomatoPHP\TomatoProducts\Models\Product;

class ProductsServices
{
    public static function getProductPrice($productID, $options=[]): ProductPriceModel|bool
    {
        $product = Product::find($productID);
        if($product){
            if($product->meta('qty')){
                $price = 0;
                $discount = 0;
                $vat = 0;
                $finalPrice = 0;

                foreach($product->meta('qty') as $key=>$item){
                    if(Str::of($key)->containsAll(array_merge($options, ['price']))){
                        $price = $item;
                    }
                    if(Str::of($key)->containsAll(array_merge($options, ['discount']))){
                        if(is_numeric($item)){
                            $discount = $item;
                        }
                    }
                    if(Str::of($key)->containsAll(array_merge($options, ['vat']))){
                        $vat = $item;
                    }

                    $finalPrice = ($price+$vat) - $discount;
                }

                if($finalPrice){
                    $collectPrice = new ProductPriceModel();
                    $collectPrice->price($price);
                    $collectPrice->discount($discount);
                    $collectPrice->vat($vat);

                    return $collectPrice;
                }
                else {
                    $price = new ProductPriceModel();
                    $price->price($product->price);
                    $price->discount($product->discount);
                    $price->vat($product->vat);

                    return $price;
                }
            }
            else {
                $price = new ProductPriceModel();
                $price->price($product->price);
                $price->discount($product->discount);
                $price->vat($product->vat);

                return $price;
            }
        }
        else {
            return false;
        }
    }
}
