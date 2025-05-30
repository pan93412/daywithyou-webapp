<?php

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

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
                        'figure'
                    ]
                ]
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
            'price' => 99.99
        ]);

        // Get the product by slug
        $response = $this->getJson("{$this->productsRoute}/{$product->slug}");

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) =>
                $json->where('data.name', $product->name)
                     ->where('data.slug', $product->slug)
                     ->where('data.price', (string)$product->price) // Cast to string for comparison
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
            'description' => 'This is a test product description that will be summarized in the API response.'
        ]);

        // Act: Call the index endpoint
        $response = $this->getJson($this->productsRoute);

        // Assert: Check the response includes a summary
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['summary']
                ]
            ])
            // Just verify the summary exists and is a string
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('data.0.summary')
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
});
