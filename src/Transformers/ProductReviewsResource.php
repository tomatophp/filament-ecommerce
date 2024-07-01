<?php

namespace TomatoPHP\FilamentEcommerce\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductReviewsResource extends JsonResource
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
            'product' => $this->product->name,
            'account' => $this->account->name,
            'rate' => $this->rate,
            'review' => $this->review,
            'is_activated' => $this->is_activated,
        ];
    }
}
