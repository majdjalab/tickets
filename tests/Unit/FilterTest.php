<?php

namespace Tests\Unit;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FilterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test filtering tickets by category.
     */
    public function test_filter_tickets_by_category(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $category1 = Category::factory()->create();
        $category2 = Category::factory()->create();

        $ticket1 = Ticket::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ticket 1',
            'description' => 'Ticket 1',
            'due_date' => now()->subDay(),
        ]);

        $ticket2 = Ticket::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ticket 2',
            'description' => 'Ticket 2',
            'due_date' => now()->addDay(),
        ]);

        $ticket1->categories()->attach($category1->id);
        $ticket2->categories()->attach($category2->id);

        $response = $this->get(route('ticket.filter', ['category_id' => $category1->id]));
        $response->assertStatus(200);
        $response->assertViewHas('tickets', function ($tickets) use ($ticket1) {
            return $tickets->contains($ticket1);
        });

        $response->assertViewHas('tickets', function ($tickets) use ($ticket2) {
            return !$tickets->contains($ticket2);
        });
    }

    public function test_filter_tickets_by_user(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $ticket1 = Ticket::factory()->create([
            'user_id' => $user1->id,
            'title' => 'Ticket 1',
            'description' => 'Ticket 1',
            'due_date' => now()->subDay(),
        ]);

        $ticket2 = Ticket::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ticket 2',
            'description' => 'Ticket 2',
            'due_date' => now()->addDay(),
        ]);


        $response = $this->get(route('ticket.filter', ['user_id' => $user1->id]));
        $response->assertStatus(200);

    }


    public function test_filter_tickets_by_date(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);



        $ticket1 = Ticket::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ticket 1',
            'description' => 'Ticket 1',
            'due_date' => '2024-08-29',
        ]);

        $ticket2 = Ticket::factory()->create([
            'user_id' => $user->id,
            'title' => 'Ticket 2',
            'description' => 'Ticket 2',
            'due_date' => now()->addDay(),
        ]);


        $response = $this->get(route('ticket.filter', ['due_date' => now()->addDay()]));
        $response->assertStatus(200);
        $response->assertViewHas('tickets', function ($tickets) use ($ticket2) {
            return $tickets->contains($ticket2);
        });

   



    }

}
