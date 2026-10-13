<?php

namespace TomatoPHP\FilamentEcommerce\Services\Cart;

use Illuminate\Support\Str;
use TomatoPHP\FilamentEcommerce\Models\Cart;
use TomatoPHP\FilamentEcommerce\Models\Product;
use TomatoPHP\FilamentEcommerce\Services\Cart\Contracts\ProductPriceModel;

class ProductsServices
{
    public static function getProductPrice($productID, $options = []): ProductPriceModel | bool
    {
        $product = Product::find($productID);
        if ($product) {
            if (is_array($product->meta('qty'))) {
                $price = 0;
                $discount = 0;
                $vat = 0;
                $finalPrice = 0;

                foreach ($product->meta('qty') as $key => $item) {
                    if (! is_numeric($item)) {
                        continue;
                    }

                    if (Str::of($key)->containsAll(array_merge($options, ['price']))) {
                        $price = $item;
                    }
                    if (Str::of($key)->containsAll(array_merge($options, ['discount']))) {
                        if (is_numeric($item)) {
                            $discount = $item;
                        }
                    }
                    if (Str::of($key)->containsAll(array_merge($options, ['vat']))) {
                        $vat = $item;
                    }

                    $finalPrice = ($price + $vat) - $discount;
                }

                if ($finalPrice) {
                    $collectPrice = new ProductPriceModel;
                    $collectPrice->price($price);
                    $collectPrice->discount($discount);
                    $collectPrice->vat($vat);

                    return $collectPrice;
                } else {
                    $price = new ProductPriceModel;
                    $price->price((float) $product->price);
                    $price->discount((float) $product->discount);
                    $price->vat((float) $product->vat);

                    return $price;
                }
            } else {
                $price = new ProductPriceModel;
                $price->price((float) $product->price);
                $price->discount((float) $product->discount);
                $price->vat((float) $product->vat);

                return $price;
            }
        } else {
            return false;
        }
    }

    /**
     * Re-price a cart row from its product, ignoring the stored amounts.
     * Returns false when the row has no product or no positive quantity.
     */
    public static function repriceCart(Cart $cart): bool
    {
        $qty = (int) $cart->qty;
        if (! $cart->product_id || $qty < 1) {
            return false;
        }

        $options = is_array($cart->options) ? array_values(array_filter($cart->options, 'is_string')) : [];
        $price = static::getProductPrice($cart->product_id, $options);
        if (! $price) {
            return false;
        }

        $cart->fill([
            'price' => $price->price,
            'discount' => $price->discount,
            'vat' => $price->vat,
            'qty' => $qty,
            'total' => max(0, $price->collect()) * $qty,
        ]);

        if ($cart->isDirty()) {
            $cart->save();
        }

        return true;
    }
}
