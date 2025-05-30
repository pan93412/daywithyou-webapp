<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin User */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            /**
             * 使用者 ID
             */
            'id' => $this->id,
            /**
             * 使用者姓名
             */
            'name' => $this->name,
            /**
             * 電子信箱
             */
            'email' => $this->email,
            /**
             * 郵遞區號
             */
            'zip' => $this->zip,
            /**
             * 電話
             */
            'phone' => $this->phone,
            /**
             * 地址
             */
            'address' => $this->address,
            /**
             * 城市
             */
            'city' => $this->city,
        ];
    }
}
