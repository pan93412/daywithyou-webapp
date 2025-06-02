<?php

use App\Models\Product;
use App\Models\State\CartState;
use App\Services\CartService;
use App\Services\RedisCartStateStorage;
use App\Services\SessionCartStateStorage;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

beforeEach(function () {
    // Clear Redis and Session before each test
    Redis::flushall();
    Session::flush();

    // Create a test product
    $this->product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 1000,
    ]);
});

afterEach(function () {
    // Clean up Redis and Session after each test
    Redis::flushall();
    Session::flush();
});

test('it creates storage instance based on config', function () {
    // Set config to use redis
    Config::set('cart.driver', 'redis');
    $redisService = new CartService;
    expect($redisService->storage())->toBeInstanceOf(RedisCartStateStorage::class);

    // Set config to use session storage
    Config::set('cart.driver', 'storage');
    $sessionService = new CartService;
    expect($sessionService->storage())->toBeInstanceOf(SessionCartStateStorage::class);
});

test('it creates storage instance based on constructor parameter', function () {
    // Override config with constructor parameter
    Config::set('cart.driver', 'storage');
    $redisService = new CartService('redis');
    expect($redisService->storage())->toBeInstanceOf(RedisCartStateStorage::class);

    Config::set('cart.driver', 'redis');
    $sessionService = new CartService('storage');
    expect($sessionService->storage())->toBeInstanceOf(SessionCartStateStorage::class);
});

test('it throws exception for invalid storage driver', function () {
    $service = new CartService('invalid_driver');

    expect(fn () => $service->storage())
        ->toThrow(\InvalidArgumentException::class, 'Invalid cart storage driver: invalid_driver');
});

test('it delegates list method to storage', function () {
    $service = new CartService;

    // Add a product to cart
    $service->set($this->product, new CartState(3));

    // Get list of cart items
    $list = $service->list();

    // Verify the list contains our product
    expect($list)->toBeArray()
        ->toHaveKey((string) $this->product->id)
        ->and($list[(string) $this->product->id]->quantity)->toBe(3);
});

test('it delegates get method to storage', function () {
    $service = new CartService;

    // Add a product to cart
    $service->set($this->product, new CartState(5));

    // Get the product from cart
    $state = $service->get($this->product);

    // Verify the state
    expect($state)->toBeInstanceOf(CartState::class)
        ->and($state->quantity)->toBe(5);
});

test('it delegates set method to storage', function () {
    $service = new CartService;

    // Set a product in cart
    $service->set($this->product, new CartState(7));

    // Verify it was set correctly
    $state = $service->get($this->product);
    expect($state->quantity)->toBe(7);
});

test('it delegates delete method to storage', function () {
    $service = new CartService;

    // Add a product to cart
    $service->set($this->product, new CartState(4));

    // Verify it exists
    expect($service->get($this->product)->quantity)->toBe(4);

    // Delete it
    $service->delete($this->product);

    // Verify it's gone
    expect($service->get($this->product)->quantity)->toBe(0);
});

test('it delegates clear method to storage', function () {
    $service = new CartService;

    // Create another product
    $product2 = Product::factory()->create(['name' => 'Another Product']);

    // Add products to cart
    $service->set($this->product, new CartState(2));
    $service->set($product2, new CartState(3));

    // Verify they exist
    expect($service->get($this->product)->quantity)->toBe(2)
        ->and($service->get($product2)->quantity)->toBe(3);

    // Clear the cart
    $service->clear();

    // Verify they're gone
    expect($service->get($this->product)->quantity)->toBe(0)
        ->and($service->get($product2)->quantity)->toBe(0);
});

test('it uses user id for authenticated users', function () {
    // Use a real User model instead of an anonymous class
    $user = new \App\Models\User;
    $user->id = 123;
    $this->actingAs($user);

    // Create service with redis driver to test user identifier
    Config::set('cart.driver', 'redis');
    $service = new CartService;

    // Set a product in cart
    $service->set($this->product, new CartState(5));

    // Verify the product was stored with the correct user identifier
    $redisKey = 'cart_state:user:123:product:'.$this->product->id;
    $data = Redis::get($redisKey);
    expect($data)->not->toBeNull();

    $decodedData = json_decode($data, true);
    expect($decodedData['quantity'])->toBe(5);
});

test('it uses session id for guest users', function () {
    // Make sure we're not authenticated
    Auth::logout();

    // Create service with redis driver
    Config::set('cart.driver', 'redis');
    $service = new CartService;

    // Set a product in cart
    $service->set($this->product, new CartState(6));

    // Verify the product was stored and can be retrieved
    $cartItem = $service->get($this->product);
    expect($cartItem->quantity)->toBe(6);
});
