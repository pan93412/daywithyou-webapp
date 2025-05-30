<?php

namespace App\Http\Resources;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Comment */
class CommentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 評論 ID
             */
            'id' => $this->id,
            /**
             * 評論內容
             */
            'content' => $this->content,
            /**
             * 評論星數 (1-5)
             */
            'rating' => $this->rating,
            /**
             * 評論者
             */
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
