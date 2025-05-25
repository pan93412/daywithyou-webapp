<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;

class InertiaProductCartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        return response()->json($this->cartService->getContents());
    }

    public function store(Product $product)
    {
        $input = request()->validate([
            'quantity' => ['required', 'numeric', 'min:1', 'max:100'],
        ]);

        $this->cartService->addItem($product, $input);
    }

    public function clear()
    {
        $this->cartService->clear();
    }
}
