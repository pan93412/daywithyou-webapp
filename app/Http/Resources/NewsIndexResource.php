<?php

namespace App\Http\Resources;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin News */
class NewsIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 最新消息標題
             */
            'title' => $this->title,
            /**
             * 最新消息摘要
             */
            'summary' => mb_strlen($this->content) > 100
                ? mb_substr($this->content, 0, 50).'……'
                : $this->content,
            /**
             * 最新消息的唯一 ID (slug)
             */
            'slug' => $this->slug,
            /**
             * 最新消息發佈日期
             */
            'created_at' => $this->created_at,
        ];
    }
}
