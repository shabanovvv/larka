<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TokenControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_issues_token_with_valid_credentials(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['token', 'token_type'])
            ->assertJson(['token_type' => 'Bearer']);
        $this->assertNotEmpty($response->json('token'));
        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_token_fails_with_invalid_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/token', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_token_fails_with_unknown_email(): void
    {
        $response = $this->postJson('/api/auth/token', [
            'email' => 'unknown@example.com',
            'password' => 'password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_token_requires_email(): void
    {
        $response = $this->postJson('/api/auth/token', [
            'password' => 'password',
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['email']);
    }

    public function test_token_requires_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/token', [
            'email' => $user->email,
        ]);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['password']);
    }

    public function test_issued_token_authenticates_profile_request(): void
    {
        $user = User::factory()->create();
        $tokenResponse = $this->postJson('/api/auth/token', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $token = $tokenResponse->json('token');

        $response = $this->getJson('/api/profile', [
            'Authorization' => 'Bearer ' . $token,
        ]);

        $response->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('name', $user->name)
            ->assertJsonPath('email', $user->email);
    }
}
