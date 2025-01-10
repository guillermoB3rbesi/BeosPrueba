<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'tax_cost' => $this->tax_cost,
            'manufacturing_cost' => $this->manufacturing_cost,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'relationships' => [
                'prices' => $this->whenLoaded('prices', PriceResource::collection($this->prices)),
                'currency' => $this->whenLoaded('currency', CurrencyResource::make($this->currency)),
            ]
        ];
    }
}
