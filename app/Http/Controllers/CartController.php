<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return response()->json($this->cartService->list());
    }

    public function store(Product $product)
    {
        $input = request()->validate([
            'quantity' => ['required', 'numeric', 'min:1', 'max:100'],
        ]);

        $currentState = $this->cartService->get($product);

        if ($currentState->quantity + $input['quantity'] > 100) {
            return;  // do not add more than 100
        }

        $newState = clone $currentState;
        $newState->quantity += $input['quantity'];
        $this->cartService->set($product, $newState);
    }

    public function remove(Product $product)
    {
        $this->cartService->delete($product);
    }

    public function clear()
    {
        $this->cartService->clear();
    }
}
