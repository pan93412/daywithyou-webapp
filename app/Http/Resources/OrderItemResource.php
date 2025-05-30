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
            /**
             * 訂單項目 ID
             */
            'id' => $this->id,
            /**
             * 數量
             */
            'quantity' => $this->quantity,
            /**
             * 建立日期
             */
            'created_at' => $this->created_at,
            /**
             * 更新日期
             */
            'updated_at' => $this->updated_at,
            /**
             * 訂單 ID
             */
            'order_id' => $this->order_id,
            /**
             * 產品 ID
             */
            'product_id' => $this->product_id,
            /**
             * 產品資訊
             */
            'product' => $this->whenLoaded('product', function () {
                return new ProductIndexResource($this->product);
            }),
        ];
    }
}
