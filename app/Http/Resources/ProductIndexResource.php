<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Product */
class ProductIndexResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'summary' => mb_strlen($this->description) > 100
                ? mb_substr($this->description, 0, 50).'……'
                : $this->description,
            'price' => $this->price,
            'figure' => $this->figure,
        ];
    }
}
