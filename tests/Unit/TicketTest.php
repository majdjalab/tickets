<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class TicketTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_create_new_ticket(): void
    {
        $user = User::factory()->create();

        $data = [
            'title' => 'test category name',
            'description' => 'test category description',
            'category' => 'test category name',
            'due_date' => now(),
        ];

        $response = $this->actingAs($user)->post('/ticket/',  $data);

        $response->assertStatus(302);
        $response->assertSessionHasNoErrors();

    }

    public function test_get_ticket(): void
    {
        $user = User::factory()->create();


        $response = $this->actingAs($user)->get('/tickets/create');

        $response->assertSessionHasNoErrors();

    }


//    public function test_user_can_delete_their_account(): void
//    {
//        $user = User::factory()->create();
//
//        $response = $this
//            ->actingAs($user)
//            ->delete('/ticket/', [
//                'title' => 'titel',
//            ]);
//
//        $response->assertSessionHasNoErrors();
//    }

}
