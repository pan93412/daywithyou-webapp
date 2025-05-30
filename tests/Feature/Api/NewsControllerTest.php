<?php

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;

uses(RefreshDatabase::class);

describe('News API', function () {
    beforeEach(function () {
        $this->newsRoute = '/api/news';
    });
    
    it('returns a collection of news items', function () {
        // Arrange: Create some news items
        News::factory()->count(3)->create();
        
        // Act: Call the index endpoint
        $response = $this->getJson($this->newsRoute);
        
        // Assert: Check the response structure and status
        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'title',
                        'summary',
                        'slug',
                        'created_at'
                    ]
                ]
            ]);
    });
    
    it('returns an empty collection when no news exists', function () {
        // Act: Call the index endpoint with no data
        $response = $this->getJson($this->newsRoute);
        
        // Assert: Check we get an empty data array
        $response->assertStatus(200)
            ->assertJsonCount(0, 'data');
    });
    
    it('can retrieve a specific news item by slug', function () {
        // Arrange: Create a news item
        $news = News::factory()->create([
            'title' => 'Test News Item',
            'content' => 'This is a test news item content.',
            'slug' => 'test-news-item'
        ]);
        
        // Get the news item by slug
        $response = $this->getJson("{$this->newsRoute}/{$news->slug}");
        
        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => 
                $json->where('data.title', 'Test News Item')
                     ->where('data.slug', 'test-news-item')
                     ->etc()
            );
    });
    
    it('returns 404 when accessing a non-existent news item', function () {
        // Act: Call a non-existent news item
        $response = $this->getJson("{$this->newsRoute}/non-existent-slug");
        
        // Assert: Check we get a 404 response
        $response->assertStatus(404);
    });
    
    it('correctly formats the summary for news items', function () {
        // Arrange: Create a news item with content
        $news = News::factory()->create([
            'content' => 'This is a test news content that will be summarized in the API response.'
        ]);
        
        // Act: Call the index endpoint
        $response = $this->getJson($this->newsRoute);
        
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
        
        // Check if the summary is either the full content (if short) or truncated (if long)
        $this->assertTrue(
            $summary === $news->content || 
            (strlen($summary) <= 52 && str_contains($summary, '…'))
        );
    });
});
