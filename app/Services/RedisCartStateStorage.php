<?php

namespace App\Services;

use App\Models\Product;
use App\Models\State\CartState;
use Illuminate\Support\Facades\Redis;

class RedisCartStateStorage implements CartStateStorage
{
    /**
     * Redis key prefix for cart items
     */
    protected string $prefix = 'cart_state:';

    public function __construct(
        private readonly string $userIdentifier,
        private readonly int $expiration = 60 * 60 * 24 * 7
    ) {}

    /**
     * {@inheritDoc}
     */
    public function list(): array
    {
        $sessionPrefix = $this->getSessionPrefix();
        $pattern = $sessionPrefix.'product:*';
        $keys = Redis::keys($pattern);
        $result = [];

        foreach ($keys as $key) {
            // Get the Redis prefix from config
            $redisPrefix = config('database.redis.options.prefix');

            // Remove Redis prefix from the key if it exists
            $actualKey = str_replace($redisPrefix, '', $key);

            // Extract the product ID from the key
            $productId = str_replace($sessionPrefix.'product:', '', $actualKey);

            // Get the data for this product
            $data = Redis::get($actualKey);

            if ($data) {
                $result[$productId] = CartState::fromArray(json_decode($data, true));
            }
        }

        return $result;
    }

    /**
     * Get the cart state for a product.
     */
    public function get(Product $product): CartState
    {
        $key = $this->getProductKey($product->id);
        $data = Redis::get($key);

        if (! $data) {
            return new CartState(0);
        }

        return CartState::fromArray(json_decode($data, true));
    }

    /**
     * Set the cart state for a product.
     */
    public function set(Product $product, CartState $data): void
    {
        $key = $this->getProductKey($product->id);
        Redis::set($key, json_encode($data->toArray()));

        // Set expiration time (e.g., 7 days)
        Redis::expire($key, $this->expiration);
    }

    /**
     * Delete the cart state for a product.
     */
    public function delete(Product $product): void
    {
        $key = $this->getProductKey($product->id);
        Redis::del($key);
    }

    /**
     * Clear the cart state.
     */
    public function clear(): void
    {
        $redisPrefix = config('database.redis.options.prefix');
        $sessionPrefix = $this->getSessionPrefix();
        $allKeys = Redis::keys("{$sessionPrefix}*");

        // Remove all "$redisPrefix" from the keys
        // FIXME: is it so ugly?
        $allKeys = array_map(function ($key) use ($redisPrefix) {
            return str_replace($redisPrefix, '', $key);
        }, $allKeys);

        Redis::del($allKeys);
    }

    protected function getSessionPrefix(): string
    {
        return $this->prefix.$this->userIdentifier.':';
    }

    /**
     * Generate a Redis key for a product
     */
    protected function getProductKey(int $productId): string
    {
        $sessionPrefix = $this->getSessionPrefix();

        return $sessionPrefix.'product:'.$productId;
    }
}
