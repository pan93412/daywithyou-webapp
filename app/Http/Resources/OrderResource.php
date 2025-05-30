<?php

namespace App\Http\Resources;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Order */
class OrderResource extends JsonResource
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
             * 收件人電子信箱
             */
            'recipient_email' => $this->recipient_email,
            /**
             * 收件人電話
             */
            'recipient_phone' => $this->recipient_phone,
            /**
             * 收件人地址
             */
            'recipient_address' => $this->recipient_address,
            /**
             * 收件人城市
             */
            'recipient_city' => $this->recipient_city,
            /**
             * 收件人郵遞區號
             */
            'recipient_zip_code' => $this->recipient_zip_code,
            /**
             * 訂單備註
             */
            'note' => $this->note,
            /**
             * 付款方式
             */
            'payment_method' => $this->payment_method,
            /**
             * 建立日期
             */
            'created_at' => $this->created_at,
            /**
             * 更新日期
             */
            'updated_at' => $this->updated_at,
            /**
             * 使用者 ID
             */
            'user_id' => $this->user_id,
            /**
             * 訂單項目集合
             */
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            /**
             * 使用者資訊
             */
            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
