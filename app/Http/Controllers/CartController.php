<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductIndexResource;
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
        $rawCarts = $this->cartService->list();
        $carts = [];

        foreach ($rawCarts as $productId => $cartState) {
            $product = Product::find($productId);

            $carts[] = ProductIndexResource::make($product)->additional([
                'quantity' => $cartState->quantity,
            ]);
        }

        return inertia('cart/index', [
            'carts' => $carts,
            'error' => session('error'),
        ]);
    }

    public function checkout()
    {
        $rawCarts = $this->cartService->list();
        $carts = [];

        foreach ($rawCarts as $productId => $cartState) {
            $product = Product::find($productId);

            $carts[] = ProductIndexResource::make($product)->additional([
                'quantity' => $cartState->quantity,
            ]);
        }

        return inertia('cart/checkout', [
            'carts' => $carts,
        ]);
    }

    public function increment(Product $product)
    {
        $input = request()->validate([
            'quantity' => ['required', 'numeric'],
        ]);

        $currentState = $this->cartService->get($product);

        if ($currentState->quantity + $input['quantity'] < 1) {
            // remove if quantity is below 1
            $this->remove($product);

            return;
        }

        $newState = clone $currentState;
        $newState->quantity = min($newState->quantity + $input['quantity'], 100);
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
