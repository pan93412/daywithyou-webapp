<?php

namespace App\Services;

use App\Models\Product;
use App\Models\State\CartState;
use Illuminate\Support\Facades\Redis;

class CartService
{
    // Default storage driver
    protected string $driver;

    /**
     * Create a new cart service instance.
     *
     * @param  string|null  $driver  The storage driver to use (storage, redis)
     */
    public function __construct(?string $driver = null)
    {
        $this->driver = $driver ?? config('cart.driver', 'storage');
    }

    public function storage(): CartStateStorage
    {
        $expiration = config('cart.expiration', 7 * 24 * 60 * 60);

        return match ($this->driver) {
            'redis' => new RedisCartStateStorage($this->getUserIdentifier(), $expiration),
            'storage' => new SessionCartStateStorage,
            default => throw new \InvalidArgumentException('Invalid cart storage driver: '.$this->driver),
        };
    }

    /**
     * @return array<string, CartState>
     */
    public function list(): array
    {
        return $this->storage()->list();
    }

    public function get(Product $product): CartState
    {
        return $this->storage()->get($product);
    }

    public function set(Product $product, CartState $data): void
    {
        $this->storage()->set($product, $data);
    }

    public function delete(Product $product): void
    {
        $this->storage()->delete($product);
    }

    public function clear(): void
    {
        $this->storage()->clear();
    }

    private function getUserIdentifier(): string
    {
        return auth()->check()
            ? 'user:'.auth()->id()
            : 'guest:'.session()->getId();
    }
}
