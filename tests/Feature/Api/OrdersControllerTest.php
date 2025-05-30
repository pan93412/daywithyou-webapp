<?php

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Orders API', function () {
    beforeEach(function () {
        $this->ordersRoute = '/api/orders';
        $this->user = User::factory()->create();
    });
    
    it('returns a collection of user orders when authenticated', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Create orders for the authenticated user
        Order::factory()->count(3)->create([
            'user_id' => $this->user->id
        ]);
        
        // Create orders for another user (should not be returned)
        $otherUser = User::factory()->create();
        Order::factory()->count(2)->create([
            'user_id' => $otherUser->id
        ]);
        
        // Act: Call the index endpoint
        $response = $this->getJson($this->ordersRoute);
        
        // Assert: Check the response structure and status
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'recipient_name',
                        'payment_method',
                        'created_at'
                    ]
                ]
            ]);
    });
    
    it('returns 401 when accessing orders without authentication', function () {
        // Act: Call the index endpoint without authentication
        $response = $this->getJson($this->ordersRoute);
        
        // Assert: Check we get a 401 response
        $response->assertStatus(401);
    });
    
    it('can retrieve a specific order by id when authenticated', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Create a product for the order item
        $product = Product::factory()->create();
        
        // Create an order with an order item
        $order = Order::factory()->create([
            'user_id' => $this->user->id,
            'recipient_name' => 'Test Recipient',
            'payment_method' => 'cash'
        ]);
        
        OrderItem::factory()->create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2
        ]);
        
        // Act: Get the order by id
        $response = $this->getJson("{$this->ordersRoute}/{$order->id}");
        
        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('data.id', $order->id)
                     ->where('data.recipient_name', 'Test Recipient')
                     ->where('data.payment_method', 'cash')
                     ->has('data.order_items')
                     ->has('data.order_items.0', fn ($json) => 
                        $json->where('quantity', 2)
                             ->where('product_id', $product->id)
                             ->etc()
                     )
                     ->etc()
            );
    });
    
    it('returns 403 when accessing an order that belongs to another user', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Create an order for another user
        $otherUser = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $otherUser->id
        ]);
        
        // Act: Try to access the order
        $response = $this->getJson("{$this->ordersRoute}/{$order->id}");
        
        // Assert: Check we get a 403 response
        $response->assertStatus(403);
    });
    
    it('can create a new order when authenticated', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Create products for the order
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        
        // Prepare order data
        $orderData = [
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
            'recipient_phone' => '1234567890',
            'recipient_address' => '123 Main St',
            'recipient_city' => 'Anytown',
            'recipient_zip_code' => '12345',
            'note' => 'Please deliver in the morning',
            'payment_method' => 'cash',
            'products' => [
                [
                    'product_id' => $product1->id,
                    'quantity' => 1
                ],
                [
                    'product_id' => $product2->id,
                    'quantity' => 2
                ]
            ]
        ];
        
        // Act: Create a new order
        $response = $this->postJson($this->ordersRoute, $orderData);
        
        // Assert: Check the response
        $response->assertStatus(201)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('data.recipient_name', 'John Doe')
                     ->where('data.recipient_email', 'john@example.com')
                     ->where('data.payment_method', 'cash')
                     ->where('data.user_id', $this->user->id)
                     ->etc()
            );
        
        // Check that the order was created in the database
        $this->assertDatabaseHas('orders', [
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
            'user_id' => $this->user->id
        ]);
        
        // Check that the order items were created
        $orderId = $response->json('data.id');
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'product_id' => $product1->id,
            'quantity' => 1
        ]);
        $this->assertDatabaseHas('order_items', [
            'order_id' => $orderId,
            'product_id' => $product2->id,
            'quantity' => 2
        ]);
    });
    
    it('validates required fields when creating an order', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Prepare incomplete order data
        $orderData = [
            'recipient_name' => 'John Doe',
            // Missing required fields
            'payment_method' => 'invalid_method',
        ];
        
        // Act: Try to create an order with invalid data
        $response = $this->postJson($this->ordersRoute, $orderData);
        
        // Assert: Check validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors([
                'recipient_email', 
                'recipient_phone',
                'recipient_address',
                'recipient_city',
                'recipient_zip_code',
                'payment_method',
            ]);
            
        // Test products validation separately
        $orderData = [
            'recipient_name' => 'John Doe',
            'recipient_email' => 'john@example.com',
            'recipient_phone' => '1234567890',
            'recipient_address' => '123 Main St',
            'recipient_city' => 'Anytown',
            'recipient_zip_code' => '12345',
            'payment_method' => 'cash',
            // Missing products
        ];
        
        $response = $this->postJson($this->ordersRoute, $orderData);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['products']);
    });
    
    it('can delete an order when authenticated', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Create an order for the authenticated user
        $order = Order::factory()->create([
            'user_id' => $this->user->id
        ]);
        
        // Act: Delete the order
        $response = $this->deleteJson("{$this->ordersRoute}/{$order->id}");
        
        // Assert: Check the response
        $response->assertStatus(204);
        
        // Check that the order was deleted from the database
        $this->assertDatabaseMissing('orders', [
            'id' => $order->id
        ]);
    });

    it('paginates the orders collection correctly', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Arrange: Create 15 orders for the authenticated user
        Order::factory()->count(15)->create([
            'user_id' => $this->user->id
        ]);
        
        // Act: Call the index endpoint with pagination parameters
        $response = $this->getJson("{$this->ordersRoute}?per_page=5&page=2");
        
        // Assert: Check the response structure and pagination
        $response->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data',
                'links' => ['first', 'last', 'prev', 'next'],
                'meta' => [
                    'current_page',
                    'from',
                    'last_page',
                    'links',
                    'path',
                    'per_page',
                    'to',
                    'total'
                ]
            ])
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.current_page', 2)
                     ->where('meta.per_page', 5)
                     ->where('meta.total', 15)
                     ->where('meta.last_page', 3)
                     ->etc()
            );
    });

    it('returns the default number of items per page when per_page is not specified', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Arrange: Create 15 orders for the authenticated user
        Order::factory()->count(15)->create([
            'user_id' => $this->user->id
        ]);
        
        // Act: Call the index endpoint without pagination parameters
        $response = $this->getJson($this->ordersRoute);
        
        // Assert: Check the default pagination (10 items per page)
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('meta.current_page', 1)
                     ->where('meta.per_page', 10)
                     ->etc()
            );
    });

    it('returns different sets of orders on different pages', function () {
        // Authenticate the user
        Sanctum::actingAs($this->user);
        
        // Arrange: Create 12 orders with unique recipient names
        $orders = [];
        for ($i = 1; $i <= 12; $i++) {
            $orders[] = Order::factory()->create([
                'user_id' => $this->user->id,
                'recipient_name' => "Recipient {$i}"
            ]);
        }
        
        // Act: Get page 1 with 5 items per page
        $responsePage1 = $this->getJson("{$this->ordersRoute}?per_page=5&page=1");
        
        // Act: Get page 2 with 5 items per page
        $responsePage2 = $this->getJson("{$this->ordersRoute}?per_page=5&page=2");
        
        // Assert: Check that different pages return different items
        $responsePage1->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        $responsePage2->assertStatus(200)
            ->assertJsonCount(5, 'data');
        
        // Get the recipient names from both pages
        $namesPage1 = collect($responsePage1->json('data'))->pluck('recipient_name')->all();
        $namesPage2 = collect($responsePage2->json('data'))->pluck('recipient_name')->all();
        
        // Check that there's no overlap between the two pages
        $this->assertEmpty(array_intersect($namesPage1, $namesPage2));
    });
});
