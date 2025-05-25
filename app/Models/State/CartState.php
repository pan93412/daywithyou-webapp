<?php

namespace App\Models\State;

use Illuminate\Support\Facades\Validator;

class CartState
{
    public int $quantity;

    public function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public function toArray(): array
    {
        return [
            'quantity' => $this->quantity,
        ];
    }

    public static function fromArray(array $data): CartState
    {
        $validator = Validator::make($data, [
            'quantity' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
        ['quantity' => $quantity] = $validator->validate();

        return new self($quantity);
    }
}
