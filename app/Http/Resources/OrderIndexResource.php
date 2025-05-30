<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 訂單 ID
             */
            'id' => $this->id,
            /**
             * 收件人姓名
             */
            'recipient_name' => $this->recipient_name,
            /**
             * 付款方式
             */
            'payment_method' => $this->payment_method,
            /**
             * 建立時間
             */
            'created_at' => $this->created_at,
        ];
    }
}
