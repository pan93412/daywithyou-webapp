<?php

use App\Models\Product;
use App\Models\State\CartState;
use App\Services\RedisCartStateStorage;
use Illuminate\Support\Facades\Redis;

beforeEach(function () {
    // Clear Redis before each test
    Redis::flushall();

    // Create a test product
    $this->product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 1000,
    ]);

    // Create the storage instance with a test user identifier
    $this->storage = new RedisCartStateStorage('test-user-123');
});

afterEach(function () {
    // Clean up Redis after each test
    Redis::flushall();
});

test('it can store and retrieve cart state', function () {
    // Create a cart state
    $cartState = new CartState(5);

    // Store it
    $this->storage->set($this->product, $cartState);

    // Retrieve it
    $retrievedState = $this->storage->get($this->product);

    // Assert it matches
    expect($retrievedState->quantity)->toBe(5);
});

test('it returns default cart state when product not in cart', function () {
    // Get state for a product that doesn't have any state saved
    $state = $this->storage->get($this->product);

    // Should return default state with quantity 0
    expect($state)->toBeInstanceOf(CartState::class);
    expect($state->quantity)->toBe(0);
});

test('it can update existing cart state', function () {
    // Create initial state
    $initialState = new CartState(3);
    $this->storage->set($this->product, $initialState);

    // Update with new state
    $updatedState = new CartState(7);
    $this->storage->set($this->product, $updatedState);

    // Retrieve and verify
    $retrievedState = $this->storage->get($this->product);
    expect($retrievedState->quantity)->toBe(7);
});

test('it can delete cart state for a product', function () {
    // Create a state
    $cartState = new CartState(4);
    $this->storage->set($this->product, $cartState);

    // Verify it exists
    $stateBeforeDelete = $this->storage->get($this->product);
    expect($stateBeforeDelete->quantity)->toBe(4);

    // Delete it
    $this->storage->delete($this->product);

    // Verify it's gone (returns default state)
    $stateAfterDelete = $this->storage->get($this->product);
    expect($stateAfterDelete->quantity)->toBe(0);
});

test('no-op if the product is not in the cart', function () {
    // Delete a product that doesn't exist
    $this->storage->delete($this->product);

    // Verify nothing happened
    expect($this->storage->get($this->product)->quantity)->toBe(0);
});

test('it can clear all cart states', function () {
    // Create multiple products
    $product1 = $this->product;
    $product2 = Product::factory()->create(['name' => 'Another Product']);

    // Add states for both products
    $this->storage->set($product1, new CartState(2));
    $this->storage->set($product2, new CartState(3));

    // Verify both exist
    expect($this->storage->get($product1)->quantity)->toBe(2)
        ->and($this->storage->get($product2)->quantity)->toBe(3);

    // Debug: Print all Redis keys before clearing
    $allKeys = Redis::keys('*');
    dump('Before clear - All Redis keys:', $allKeys);

    // Clear all
    $this->storage->clear();

    // Debug: Print all Redis keys after clearing
    $allKeys = Redis::keys('*');
    dump('After clear - All Redis keys:', $allKeys);

    // Verify both are gone
    expect($this->storage->get($product1)->quantity)->toBe(0)
        ->and($this->storage->get($product2)->quantity)->toBe(0);
});

test('it sets expiration time on cart state', function () {
    // Create a storage with a very short expiration (1 second)
    $shortExpirationStorage = new RedisCartStateStorage('test-user-123', 1);

    // Set a cart state
    $cartState = new CartState(6);
    $shortExpirationStorage->set($this->product, $cartState);

    // Verify it exists
    expect($shortExpirationStorage->get($this->product)->quantity)->toBe(6);

    // Wait for expiration
    sleep(2);

    // Verify it's gone
    expect($shortExpirationStorage->get($this->product)->quantity)->toBe(0);
});

test('it can list all cart states', function () {
    // Create multiple products
    $product1 = $this->product;
    $product2 = Product::factory()->create(['name' => 'Another Product']);
    $product3 = Product::factory()->create(['name' => 'Third Product']);

    // Add states for products
    $this->storage->set($product1, new CartState(2));
    $this->storage->set($product2, new CartState(3));
    // Intentionally not setting state for product3 to test it's not included

    // Get the list of cart states
    $cartStates = $this->storage->list();

    // Verify the list contains the correct items
    expect($cartStates)->toBeArray()
        ->toHaveCount(2)
        ->toHaveKeys([(string) $product1->id, (string) $product2->id])
        ->not->toHaveKey((string) $product3->id)
        ->and($cartStates[(string) $product1->id])->toBeInstanceOf(CartState::class)
        // Verify the cart states have the correct quantities
        ->and($cartStates[(string) $product1->id]->quantity)->toBe(2)
        ->and($cartStates[(string) $product2->id])->toBeInstanceOf(CartState::class)
        ->and($cartStates[(string) $product2->id]->quantity)->toBe(3);
});
