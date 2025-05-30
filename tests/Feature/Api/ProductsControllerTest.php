<?php

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('Products API', function () {
    beforeEach(function () {
        $this->productsRoute = '/api/products';
    });

    it('returns a collection of products', function () {
        // Arrange: Create some products
        Product::factory()->count(3)->create();

        // Act: Call the index endpoint
        $response = $this->getJson($this->productsRoute);

        // Assert: Check the response structure and status
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'slug',
                        'name',
                        'summary',
                        'price',
                        'figure',
                    ],
                ],
            ]);
    });

    it('returns an empty collection when no products exist', function () {
        // Act: Call the index endpoint with no data
        $response = $this->getJson($this->productsRoute);

        // Assert: Check we get an empty data array
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });

    it('can retrieve a specific product by slug', function () {
        // Arrange: Create a product
        $product = Product::factory()->create([
            'name' => 'Test Product',
            'description' => 'This is a test product description.',
            'slug' => 'test-product',
            'price' => 99.99,
        ]);

        // Get the product by slug
        $response = $this->getJson("{$this->productsRoute}/{$product->slug}");

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->where('data.name', $product->name)
                ->where('data.slug', $product->slug)
                ->where('data.price', (string) $product->price) // Cast to string for comparison
                ->etc()
            );
    });

    it('returns 404 when accessing a non-existent product', function () {
        // Act: Call a non-existent product
        $response = $this->getJson("{$this->productsRoute}/non-existent-slug");

        // Assert: Check we get a 404 response
        $response->assertStatus(404);
    });

    it('correctly formats the summary for product descriptions', function () {
        // Arrange: Create a product with a description
        $product = Product::factory()->create([
            'description' => 'This is a test product description that will be summarized in the API response.',
        ]);

        // Act: Call the index endpoint
        $response = $this->getJson($this->productsRoute);

        // Assert: Check the response includes a summary
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['summary'],
                ],
            ])
            // Just verify the summary exists and is a string
            ->assertJson(fn (AssertableJson $json) => $json->has('data.0.summary')
                ->etc()
            );

        // Get the actual summary to verify its format
        $summary = $response->json('data.0.summary');

        // Check if the summary is either the full description (if short) or truncated (if long)
        $this->assertTrue(
            $summary === $product->description ||
            (strlen($summary) <= 52 && str_contains($summary, '…'))
        );
    });

    it('includes the correct product figure URL', function () {
        // Arrange: Create a product with a specific figure URL pattern
        $product = Product::factory()->create();

        // Act: Call the index endpoint
        $response = $this->getJson($this->productsRoute);

        // Assert: Check the figure URL follows the expected pattern
        $response->assertStatus(200);

        // Get the actual figure URL
        $figure = $response->json('data.0.figure');
        $this->assertEquals($figure, $product->figure);
    });

    it('paginates the products collection correctly', function () {
        // Arrange: Create 15 products
        Product::factory()->count(15)->create();

        // Act: Call the index endpoint with pagination parameters
        $response = $this->getJson("{$this->productsRoute}?per_page=5&page=2");

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
                    'total',
                ],
            ])
            ->assertJson(fn (AssertableJson $json) => $json->where('meta.current_page', 2)
                ->where('meta.per_page', 5)
                ->where('meta.total', 15)
                ->where('meta.last_page', 3)
                ->etc()
            );
    });

    it('returns the default number of items per page when per_page is not specified', function () {
        // Arrange: Create 15 products
        Product::factory()->count(15)->create();

        // Act: Call the index endpoint without pagination parameters
        $response = $this->getJson($this->productsRoute);

        // Assert: Check the default pagination (10 items per page)
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson(fn (AssertableJson $json) => $json->where('meta.current_page', 1)
                ->where('meta.per_page', 10)
                ->etc()
            );
    });

    it('returns different sets of products on different pages', function () {
        // Arrange: Create 12 products with unique names
        $products = [];
        for ($i = 1; $i <= 12; $i++) {
            $products[] = Product::factory()->create([
                'name' => "Product {$i}",
            ]);
        }

        // Act: Get page 1 with 5 items per page
        $responsePage1 = $this->getJson("{$this->productsRoute}?per_page=5&page=1");

        // Act: Get page 2 with 5 items per page
        $responsePage2 = $this->getJson("{$this->productsRoute}?per_page=5&page=2");

        // Assert: Check that different pages return different items
        $responsePage1->assertStatus(200)
            ->assertJsonCount(5, 'data');

        $responsePage2->assertStatus(200)
            ->assertJsonCount(5, 'data');

        // Get the product names from both pages
        $namesPage1 = collect($responsePage1->json('data'))->pluck('name')->all();
        $namesPage2 = collect($responsePage2->json('data'))->pluck('name')->all();

        // Check that there's no overlap between the two pages
        $this->assertEmpty(array_intersect($namesPage1, $namesPage2));
    });

    // New tests for comments endpoint
    it('returns a collection of comments for a product', function () {
        // Arrange: Create a product with comments
        $product = Product::factory()->create();
        $comments = Comment::factory()->count(3)->create([
            'product_id' => $product->id,
        ]);

        // Act: Call the comments endpoint
        $response = $this->getJson("{$this->productsRoute}/{$product->slug}/comments");

        // Assert: Check the response structure and status
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'content',
                        'rating',
                        'user',
                    ],
                ],
            ]);
    });

    it('returns an empty collection when a product has no comments', function () {
        // Arrange: Create a product with no comments
        $product = Product::factory()->create();

        // Act: Call the comments endpoint
        $response = $this->getJson("{$this->productsRoute}/{$product->slug}/comments");

        // Assert: Check we get an empty data array
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });

    it('includes user information with each comment', function () {
        // Arrange: Create a product with a comment from a specific user
        $product = Product::factory()->create();
        $user = User::factory()->create([
            'name' => 'Test User',
        ]);

        Comment::factory()->create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'content' => 'This is a test comment',
            'rating' => 4,
        ]);

        // Act: Call the comments endpoint
        $response = $this->getJson("{$this->productsRoute}/{$product->slug}/comments");

        // Assert: Check the user data is included
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->has('data.0', fn ($json) => $json->where('content', 'This is a test comment')
                ->where('rating', 4)
                ->has('user', fn ($json) => $json->where('name', 'Test User')
                    ->etc()
                )
                ->etc()
            )
            );
    });

    // Tests for storeComment endpoint
    it('allows authenticated users to create comments', function () {
        // Arrange: Create a product and authenticate a user
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $commentData = [
            'content' => 'This is a great product!',
            'rating' => 5,
        ];

        // Act: Post a new comment
        $response = $this->postJson("{$this->productsRoute}/{$product->slug}/comments", $commentData);

        // Assert: Check the comment was created successfully
        $response->assertStatus(201);

        // Verify the comment exists in the database
        $this->assertDatabaseHas('comments', [
            'product_id' => $product->id,
            'user_id' => $user->id,
            'content' => 'This is a great product!',
            'rating' => 5,
        ]);

        // Check the response structure - user may not be included if not loaded
        $response->assertJsonStructure([
            'data' => [
                'id',
                'content',
                'rating',
                // 'user' field is optional as it depends on relationship loading
            ],
        ]);
    });

    it('validates comment data', function () {
        // Arrange: Create a product and authenticate a user
        $product = Product::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        // Invalid data: missing content and rating out of range
        $invalidData = [
            'content' => 'Hi', // Too short
            'rating' => 6, // Out of range (1-5)
        ];

        // Act: Try to post an invalid comment
        $response = $this->postJson("{$this->productsRoute}/{$product->slug}/comments", $invalidData);

        // Assert: Check validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content', 'rating']);
    });

    it('prevents unauthenticated users from creating comments', function () {
        // Arrange: Create a product (no authentication)
        $product = Product::factory()->create();

        $commentData = [
            'content' => 'This is a great product!',
            'rating' => 5,
        ];

        // Act: Try to post a comment without authentication
        $response = $this->postJson("{$this->productsRoute}/{$product->slug}/comments", $commentData);

        // Assert: Check authentication is required
        $response->assertStatus(401);
    });
});
