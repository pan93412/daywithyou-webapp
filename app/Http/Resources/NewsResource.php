<?php

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin News */
class NewsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 最新消息標題
             */
            'title' => $this->title,
            /**
             * 最新消息內容
             */
            'content' => $this->content,
            /**
             * 最新消息的可讀 ID (slug)
             */
            'slug' => $this->slug,
            /**
             * 建立日期
             */
            'created_at' => $this->created_at,
            /**
             * 更新日期
             */
            'updated_at' => $this->updated_at,
        ];
    }
}
