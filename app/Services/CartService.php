<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class CartService
{
    // Default storage driver
    protected string $driver;

    // User identifier
    protected string $userIdentifier;

    // Cart key prefix
    protected string $prefix = 'cart:';

    /**
     * Create a new cart service instance.
     *
     * @param  string|null  $driver  The storage driver to use (session, redis)
     * @param  string|null  $userIdentifier  The user identifier (session id or user id)
     */
    public function __construct(?string $driver = null, ?string $userIdentifier = null)
    {
        $this->driver = $driver ?? config('cart.driver', 'redis');
        $this->userIdentifier = $userIdentifier ?? $this->resolveUserIdentifier();
    }

    /**
     * Get the cart contents.
     */
    public function getContents(): array
    {
        return $this->driver === 'redis'
            ? $this->getRedisContents()
            : $this->getSessionContents();
    }

    /**
     * Add an item to the cart.
     */
    public function addItem(Product $product, array $data): void
    {
        if ($this->driver === 'redis') {
            $this->addRedisItem($product, $data);
        } else {
            $this->addSessionItem($product, $data);
        }
    }

    /**
     * Clear the cart.
     */
    public function clear(): void
    {
        if ($this->driver === 'redis') {
            $this->clearRedis();
        } else {
            $this->clearSession();
        }
    }

    /**
     * Get the cart contents from session.
     */
    protected function getSessionContents(): array
    {
        return Session::get('cart', []);
    }

    /**
     * Add an item to the session cart.
     */
    protected function addSessionItem(Product $product, array $data): void
    {
        Session::put(
            'cart', [
                ...Session::get('cart', []),
                $product->id => $data,
            ],
        );
    }

    /**
     * Clear the session cart.
     */
    protected function clearSession(): void
    {
        Session::forget('cart');
    }

    /**
     * Get the cart contents from Redis.
     */
    protected function getRedisContents(): array
    {
        $cartData = Redis::hgetall($this->getCartKey());

        if (empty($cartData)) {
            return [];
        }

        // Convert Redis hash to the expected format
        return array_map(function ($data) {
            return json_decode($data, true);
        }, $cartData);
    }

    /**
     * Add an item to the Redis cart.
     */
    protected function addRedisItem(Product $product, array $data): void
    {
        Redis::hset(
            $this->getCartKey(),
            $product->id,
            json_encode($data)
        );

        // Set expiration time (e.g., 7 days)
        Redis::expire($this->getCartKey(), config('cart.expiration', 60 * 60 * 24 * 7));
    }

    /**
     * Clear the Redis cart.
     */
    protected function clearRedis(): void
    {
        Redis::del($this->getCartKey());
    }

    /**
     * Get the cart key for Redis.
     */
    protected function getCartKey(): string
    {
        return $this->prefix.$this->userIdentifier;
    }

    /**
     * Resolve the user identifier.
     */
    protected function resolveUserIdentifier(): string
    {
        // Use user ID if authenticated, otherwise use session ID
        if (auth()->check()) {
            return 'user:'.auth()->id();
        }

        return 'guest:'.session()->getId();
    }
}
