<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use refreshDatabase, WithFaker;
    /**
     * A basic feature test example.
     */
    public function test_categories(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/categories');

        $response->assertStatus(200);
    }

}
