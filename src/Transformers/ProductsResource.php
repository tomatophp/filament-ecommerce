<?php

namespace TomatoPHP\FilamentEcommerce\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'barcode' => $this->barcode,
            'type' => $this->type,
            'about' => $this->about,
            'price' => $this->price,
            'discount' => $this->discount,
            'discount_to' => $this->discount_to,
            'vat' => $this->vat,
            'description' => $this->description,
            'details' => $this->details,
            'category' => $this->category,
            'is_shipped' => $this->is_shipped,
            'is_activated' => $this->is_activated,
            'is_trend' => $this->is_trend,
            'is_in_stock' => $this->is_in_stock,
            'has_options' => $this->has_options,
            'has_multi_price' => $this->has_multi_price,
            'has_unlimited_stock' => $this->has_unlimited_stock,
            'has_max_cart' => $this->has_max_cart,
            'min_cart' => $this->min_cart,
            'max_cart' => $this->max_cart,
            'has_stock_alert' => $this->has_stock_alert,
            'min_stock_alert' => $this->min_stock_alert,
            'max_stock_alert' => $this->max_stock_alert,
            'categories' => $this->categories,
            'tags' => $this->tags,
            'images' => $this->getMedia('images'),
            'feature_image' => $this->getMedia('feature_image'),
            "options" =>   $this->meta('options') ?: (object)[],
            "qty" =>   $this->meta('qty') ?: (object)[],
            "prices" =>   $this->meta('prices') ?: [],
            "brand" =>   $this->meta('brand') ?: null,
            "unit" =>   $this->meta('unit') ?: null,
            "weight" =>   $this->meta('weight') ?: null,
        ];
    }
}
