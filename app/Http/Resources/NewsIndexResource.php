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
            'title' => $this->title,
            'summary' => mb_strlen($this->content) > 100
                ? mb_substr($this->content, 0, 50).'……'
                : $this->content,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
        ];
    }
}
