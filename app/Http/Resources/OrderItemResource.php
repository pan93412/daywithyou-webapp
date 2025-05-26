<?php

namespace App\Http\Resources;

use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin OrderItem */
class OrderItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'product' => $this->whenLoaded('product', function () {
                return new ProductIndexResource($this->product);
            }),
        ];
    }
}
