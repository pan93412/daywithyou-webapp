<?php

namespace App\Services;

use App\Models\Product;
use App\Models\State\CartState;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SessionCartStateStorage implements CartStateStorage
{
    /**
     * Session key prefix for cart items
     */
    protected string $prefix = 'cart_state';

    /**
     * {@inheritDoc}
     */
    public function list(): array
    {
        try {
            $sessionKey = $this->getSessionKey();
            $cartData = Session::get($sessionKey, []);
            $result = [];

            foreach ($cartData as $productId => $data) {
                $result[$productId] = CartState::fromArray($data);
            }

            return $result;
        } catch (Exception $e) {
            Log::error('Session cart list error: '.$e->getMessage(), [
                'exception' => $e,
            ]);

            return [];
        }
    }

    /**
     * Get the cart state for a product.
     */
    public function get(Product $product): CartState
    {
        try {
            $sessionKey = $this->getSessionKey();
            $cartData = Session::get($sessionKey, []);

            if (! isset($cartData[$product->id])) {
                return new CartState(0);
            }

            return CartState::fromArray($cartData[$product->id]);
        } catch (Exception $e) {
            Log::error('Session cart get error: '.$e->getMessage(), [
                'exception' => $e,
                'product_id' => $product->id,
            ]);

            return new CartState(0);
        }
    }

    /**
     * Set the cart state for a product.
     */
    public function set(Product $product, CartState $data): void
    {
        try {
            $sessionKey = $this->getSessionKey();
            $cartData = Session::get($sessionKey, []);

            $cartData[$product->id] = $data->toArray();

            Session::put($sessionKey, $cartData);
        } catch (Exception $e) {
            Log::error('Session cart set error: '.$e->getMessage(), [
                'exception' => $e,
                'product_id' => $product->id,
                'data' => $data->toArray(),
            ]);
        }
    }

    /**
     * Delete the cart state for a product.
     */
    public function delete(Product $product): void
    {
        try {
            $sessionKey = $this->getSessionKey();
            $cartData = Session::get($sessionKey, []);

            if (isset($cartData[$product->id])) {
                unset($cartData[$product->id]);
                Session::put($sessionKey, $cartData);
            }
        } catch (Exception $e) {
            Log::error('Session cart delete error: '.$e->getMessage(), [
                'exception' => $e,
                'product_id' => $product->id,
            ]);
        }
    }

    /**
     * Clear the cart state of this session.
     */
    public function clear(): void
    {
        try {
            Session::forget($this->getSessionKey());
        } catch (Exception $e) {
            Log::error('Session cart clear error: '.$e->getMessage(), [
                'exception' => $e,
            ]);
        }
    }

    /**
     * Get the session key for cart data
     */
    protected function getSessionKey(): string
    {
        return $this->prefix;
    }
}
