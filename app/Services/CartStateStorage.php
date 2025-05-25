<?php

namespace App\Services;

use App\Models\Product;
use App\Models\State\CartState;

interface CartStateStorage
{
    /**
     * List the cart state of this session.
     *
     * @return array<string, CartState> product_id => CartState
     */
    public function list(): array;

    /**
     * Get the cart state for a product.
     */
    public function get(Product $product): CartState;

    /**
     * Set the cart state for a product.
     */
    public function set(Product $product, CartState $data): void;

    /**
     * Delete the cart state for a product.
     */
    public function delete(Product $product): void;

    /**
     * Clear the cart state of this session.
     */
    public function clear(): void;
}
