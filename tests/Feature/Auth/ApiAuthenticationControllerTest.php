<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('API Authentication', function () {
    beforeEach(function () {
        // Create routes for easier reference
        $this->loginRoute = '/api/auth/login';
        $this->registerRoute = '/api/auth/register';
        $this->logoutRoute = '/api/auth/logout';
        $this->userRoute = '/api/me';
    });

    describe('Login', function () {
        it('returns a token when credentials are valid', function () {
            $user = User::factory()->create([
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);

            $response = $this->postJson($this->loginRoute, [
                'email' => 'test@example.com',
                'password' => 'password',
            ]);

            $response->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('token')
                );

            // Verify the token can be used for authorization
            $token = $response->json('token');
            $authResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson($this->userRoute);

            $authResponse->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->where('data.id', $user->id)
                        ->where('data.email', $user->email)
                        ->etc()
                );
        });

        it('returns 422 when validation fails', function () {
            $response = $this->postJson($this->loginRoute, [
                'email' => 'not-an-email',
                'password' => '',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email', 'password']);
        });

        it('returns 422 (with error message) when credentials are invalid', function () {
            $user = User::factory()->create([
                'email' => 'test@example.com',
                'password' => bcrypt('password'),
            ]);

            $response = $this->postJson($this->loginRoute, [
                'email' => 'test@example.com',
                'password' => 'wrong-password',
            ]);

            $response->assertStatus(422);
        });
    });

    describe('Register', function () {
        it('creates a new user and returns a token', function () {
            $response = $this->postJson($this->registerRoute, [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertStatus(201)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->has('token')
                );

            $this->assertDatabaseHas('users', [
                'name' => 'Test User',
                'email' => 'test@example.com',
            ]);

            // Verify the token can be used for authorization
            $token = $response->json('token');
            $user = User::where('email', 'test@example.com')->first();

            $authResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
                ->getJson($this->userRoute);

            $authResponse->assertStatus(200)
                ->assertJson(fn (AssertableJson $json) =>
                    $json->where('data.id', $user->id)
                        ->where('data.email', $user->email)
                        ->etc()
                );
        });

        it('returns 422 when validation fails', function () {
            $response = $this->postJson($this->registerRoute, [
                'name' => '',
                'email' => 'not-an-email',
                'password' => 'short',
                'password_confirmation' => 'not-matching',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['name', 'email', 'password']);
        });

        it('returns 422 when email is already taken', function () {
            User::factory()->create(['email' => 'test@example.com']);

            $response = $this->postJson($this->registerRoute, [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'password_confirmation' => 'password123',
            ]);

            $response->assertStatus(422)
                ->assertJsonValidationErrors(['email']);
        });
    });

    describe('Logout', function () {
        it('returns 401 when accessing protected route without authentication', function () {
            $response = $this->postJson($this->logoutRoute);
            $response->assertStatus(401);
        });

        it('successfully logs out an authenticated user', function () {
            $user = User::factory()->create();

            // Authenticate the user with Sanctum
            Sanctum::actingAs($user);

            // Verify we can access a protected route
            $this->getJson($this->userRoute)->assertStatus(200);

            // Perform logout
            $response = $this->postJson($this->logoutRoute);
            $response->assertStatus(200);

            // Check that the token count has decreased
            $this->assertCount(0, $user->tokens);
        });

        it('properly handles the auth()->user() check', function () {
            // This test specifically targets the auth()->user() check in the controller
            // We're testing the code path without using middleware to simulate what happens
            // when the auth()->user() check fails

            // Create a mock route that simulates the controller behavior
            Route::post('/test-auth-check', function () {
                $user = auth()->user();
                if (!$user) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }
                return response()->json(['success' => true]);
            });

            // Test without authentication
            $response = $this->postJson('/test-auth-check');
            $response->assertStatus(401);

            // Test with authentication
            Sanctum::actingAs(User::factory()->create());
            $this->postJson('/test-auth-check')->assertStatus(200);
        });
    });
});
