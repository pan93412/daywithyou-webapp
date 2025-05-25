<?php

namespace App\Http\Controllers;

use App\Models\Product;

class InertiaProductCartController extends Controller
{
    public function index()
    {
        return response()->json(session('cart', []));
    }

    public function store(Product $product)
    {
        $input = request()->validate([
            'quantity' => ['required', 'numeric', 'min:1', 'max:100']
        ]);

        session([
            'cart' => [
                ...session('cart', []),
                $product->id => $input,
            ],
        ]);
    }
}
