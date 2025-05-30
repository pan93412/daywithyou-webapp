<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 產品 ID
             */
            'id' => $this->id,
            /**
             * 產品的可讀 ID (slug)
             */
            'slug' => $this->slug,
            /**
             * 產品名稱
             */
            'name' => $this->name,
            /**
             * 產品描述
             */
            'description' => $this->description,
            /**
             * 產品價格
             */
            'price' => $this->price,
            /**
             * 產品圖片 URL
             */
            'figure' => $this->figure,
            /**
             * 產品建立日期
             */
            'created_at' => $this->created_at,
        ];
    }
}
