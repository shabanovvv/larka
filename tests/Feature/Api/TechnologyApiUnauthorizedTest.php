<?php

namespace Tests\Feature\Api;

use App\Models\Technology;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Тесты доступа к API технологий без авторизации (ожидаем 401).
 */
class TechnologyApiUnauthorizedTest extends TestCase
{
    use RefreshDatabase;

    public function test_technologies_index_returns_401_without_token(): void
    {
        $response = $this->getJson('/api/technologies');

        $response->assertUnauthorized();
    }

    public function test_technologies_show_returns_401_without_token(): void
    {
        $technology = Technology::factory()->create();

        $response = $this->getJson('/api/technologies/' . $technology->id);

        $response->assertUnauthorized();
    }

    public function test_technologies_store_returns_401_without_token(): void
    {
        $response = $this->postJson('/api/technologies', [
            'name' => 'X',
            'slug' => 'x',
        ]);

        $response->assertUnauthorized();
    }

    public function test_technologies_update_returns_401_without_token(): void
    {
        $technology = Technology::factory()->create();

        $response = $this->putJson('/api/technologies/' . $technology->id, [
            'name' => 'Y',
            'slug' => 'y',
        ]);

        $response->assertUnauthorized();
    }

    public function test_technologies_destroy_returns_401_without_token(): void
    {
        $technology = Technology::factory()->create();

        $response = $this->deleteJson('/api/technologies/' . $technology->id);

        $response->assertUnauthorized();
    }
}
