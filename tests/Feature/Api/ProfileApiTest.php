<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Тесты API личного кабинета (GET /api/profile).
 */
class ProfileApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_returns_current_user_data_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/profile');

        $response->assertOk()
            ->assertJsonPath('id', $user->id)
            ->assertJsonPath('name', $user->name)
            ->assertJsonPath('email', $user->email);
    }

    public function test_profile_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/profile');

        $response->assertUnauthorized();
    }

    public function test_profile_returns_401_with_invalid_token(): void
    {
        $response = $this->getJson('/api/profile', [
            'Authorization' => 'Bearer invalid-token',
        ]);

        $response->assertUnauthorized();
    }

    public function test_profile_works_with_sanctum_acting_as(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user, ['*']);

        $response = $this->getJson('/api/profile');

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]);
    }
}
