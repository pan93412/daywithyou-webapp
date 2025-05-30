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
                        'created_at',
                    ],
                ],
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
            'slug' => 'test-news-item',
        ]);

        // Get the news item by slug
        $response = $this->getJson("{$this->newsRoute}/{$news->slug}");

        // Assert the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->where('data.title', 'Test News Item')
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
            'content' => 'This is a test news content that will be summarized in the API response.',
        ]);

        // Act: Call the index endpoint
        $response = $this->getJson($this->newsRoute);

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

        // Check if the summary is either the full content (if short) or truncated (if long)
        $this->assertTrue(
            $summary === $news->content ||
            (strlen($summary) <= 52 && str_contains($summary, '…'))
        );
    });

    it('paginates the news collection correctly', function () {
        // Arrange: Create 15 news items
        News::factory()->count(15)->create();

        // Act: Call the index endpoint with pagination parameters
        $response = $this->getJson("{$this->newsRoute}?per_page=5&page=2");

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
        // Arrange: Create 15 news items
        News::factory()->count(15)->create();

        // Act: Call the index endpoint without pagination parameters
        $response = $this->getJson($this->newsRoute);

        // Assert: Check the default pagination (10 items per page)
        $response->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJson(fn (AssertableJson $json) => $json->where('meta.current_page', 1)
                ->where('meta.per_page', 10)
                ->etc()
            );
    });

    it('returns different sets of news items on different pages', function () {
        // Arrange: Create 12 news items with unique titles
        $news = [];
        for ($i = 1; $i <= 12; $i++) {
            $news[] = News::factory()->create([
                'title' => "News Item {$i}",
            ]);
        }

        // Act: Get page 1 with 5 items per page
        $responsePage1 = $this->getJson("{$this->newsRoute}?per_page=5&page=1");

        // Act: Get page 2 with 5 items per page
        $responsePage2 = $this->getJson("{$this->newsRoute}?per_page=5&page=2");

        // Assert: Check that different pages return different items
        $responsePage1->assertStatus(200)
            ->assertJsonCount(5, 'data');

        $responsePage2->assertStatus(200)
            ->assertJsonCount(5, 'data');

        // Get the titles from both pages
        $titlesPage1 = collect($responsePage1->json('data'))->pluck('title')->all();
        $titlesPage2 = collect($responsePage2->json('data'))->pluck('title')->all();

        // Check that there's no overlap between the two pages
        $this->assertEmpty(array_intersect($titlesPage1, $titlesPage2));
    });
});
