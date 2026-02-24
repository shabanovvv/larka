<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты публичного API пользователя (GET /api/user/{id}).
 */
class UserApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_show_returns_user_by_id_without_auth(): void
    {
        $user = User::factory()->create();

        $response = $this->getJson('/api/user/' . $user->id);

        $response->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.name', $user->name)
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_user_show_returns_404_for_missing_user(): void
    {
        $response = $this->getJson('/api/user/99999');

        $response->assertNotFound();
    }
}
