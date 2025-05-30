<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

describe('User API', function () {
    beforeEach(function () {
        $this->userRoute = '/api/me';
    });

    it('returns authenticated user information', function () {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'zip' => '12345',
            'phone' => '123-456-7890',
            'address' => '123 Test St',
            'city' => 'Test City',
        ]);

        Sanctum::actingAs($user);

        // Act: Call the index endpoint
        $response = $this->getJson($this->userRoute);

        // Assert: Check the response structure and content
        $response->assertStatus(200)
            ->assertJson(fn (AssertableJson $json) => $json->where('data.name', 'Test User')
                ->where('data.email', 'test@example.com')
                ->where('data.zip', '12345')
                ->where('data.phone', '123-456-7890')
                ->where('data.address', '123 Test St')
                ->where('data.city', 'Test City')
                ->etc()
            );
    });

    it('requires authentication to access user information', function () {
        // Act: Call the index endpoint without authentication
        $response = $this->getJson($this->userRoute);

        // Assert: Check authentication is required
        $response->assertStatus(401);
    });

    it('allows users to update their profile information', function () {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // New profile data
        $updatedData = [
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'zip' => '54321',
            'phone' => '098-765-4321',
            'address' => '456 Updated St',
            'city' => 'Updated City',
        ];

        // Act: Update the user profile
        $response = $this->putJson($this->userRoute, $updatedData);

        // Assert: Check the update was successful
        $response->assertStatus(200);

        // Verify the database was updated
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
            'zip' => '54321',
            'phone' => '098-765-4321',
            'address' => '456 Updated St',
            'city' => 'Updated City',
        ]);
    });

    it('validates profile update data', function () {
        // Arrange: Create and authenticate a user
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // Invalid data
        $invalidData = [
            'name' => '', // Required
            'email' => 'not-an-email', // Invalid email format
        ];

        // Act: Try to update with invalid data
        $response = $this->putJson($this->userRoute, $invalidData);

        // Assert: Check validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    });

    it('allows users to update their password', function () {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Sanctum::actingAs($user);

        // Password update data
        $passwordData = [
            'current_password' => 'password123',
            'password' => 'newPassword456',
        ];

        // Act: Update the password
        $response = $this->putJson("{$this->userRoute}/password", $passwordData);

        // Assert: Check the update was successful
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Password updated successfully.',
            ]);

        // Verify the password was updated (by checking it's different)
        $updatedUser = User::find($user->id);
        $this->assertFalse(Hash::check('password123', $updatedUser->password));
        $this->assertTrue(Hash::check('newPassword456', $updatedUser->password));
    });

    it('validates current password when updating password', function () {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Sanctum::actingAs($user);

        // Incorrect current password
        $passwordData = [
            'current_password' => 'wrongPassword',
            'password' => 'newPassword456',
        ];

        // Act: Try to update with incorrect current password
        $response = $this->putJson("{$this->userRoute}/password", $passwordData);

        // Assert: Check validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['current_password']);
    });

    it('allows users to delete their account with correct password', function () {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Sanctum::actingAs($user);

        // Act: Delete the account
        $response = $this->deleteJson($this->userRoute, [
            'password' => 'password123',
        ]);

        // Assert: Check the deletion was successful
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Account deleted successfully.',
            ]);

        // Verify the user was deleted
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    });

    it('prevents account deletion with incorrect password', function () {
        // Arrange: Create a user with a known password
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        Sanctum::actingAs($user);

        // Act: Try to delete with incorrect password
        $response = $this->deleteJson($this->userRoute, [
            'password' => 'wrongPassword',
        ]);

        // Assert: Check validation errors
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);

        // Verify the user was not deleted
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
        ]);
    });
});
