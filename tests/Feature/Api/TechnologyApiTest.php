<?php

namespace Tests\Feature\Api;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

/**
 * Тесты CRUD API технологий (авторизация по токену).
 */
class TechnologyApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sanctum::actingAs(User::factory()->create(), ['*']);
    }

    public function test_technologies_index_returns_list(): void
    {
        Technology::factory()->count(2)->create();

        $response = $this->getJson('/api/technologies');

        $response->assertOk()
            ->assertJsonStructure(['data'])
            ->assertJsonCount(2, 'data');
    }

    public function test_technologies_show_returns_one(): void
    {
        $technology = Technology::factory()->create(['name' => 'PHP', 'slug' => 'php']);

        $response = $this->getJson('/api/technologies/' . $technology->id);

        $response->assertOk()
            ->assertJsonPath('data.id', $technology->id)
            ->assertJsonPath('data.name', 'PHP')
            ->assertJsonPath('data.slug', 'php');
    }

    public function test_technologies_store_creates_with_valid_data(): void
    {
        $response = $this->postJson('/api/technologies', [
            'name' => 'Laravel',
            'slug' => 'laravel',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Laravel')
            ->assertJsonPath('data.slug', 'laravel');
        $this->assertDatabaseHas('technologies', ['name' => 'Laravel', 'slug' => 'laravel']);
    }

    public function test_technologies_store_generates_slug_from_name_if_empty(): void
    {
        $response = $this->postJson('/api/technologies', [
            'name' => 'Vue.js',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('technologies', ['name' => 'Vue.js']);
        $created = Technology::query()->where('name', 'Vue.js')->first();
        $this->assertNotEmpty($created->slug);
    }

    public function test_technologies_update_modifies_resource(): void
    {
        $technology = Technology::factory()->create(['name' => 'Old', 'slug' => 'old']);

        $response = $this->putJson('/api/technologies/' . $technology->id, [
            'name' => 'Updated',
            'slug' => 'updated',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Updated')
            ->assertJsonPath('data.slug', 'updated');
        $technology->refresh();
        $this->assertSame('Updated', $technology->name);
    }

    public function test_technologies_destroy_deletes_resource(): void
    {
        $technology = Technology::factory()->create();

        $response = $this->deleteJson('/api/technologies/' . $technology->id);

        $response->assertNoContent();
        $this->assertDatabaseMissing('technologies', ['id' => $technology->id]);
    }

}
