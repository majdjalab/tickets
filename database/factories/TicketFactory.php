<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'user_id' => User::factory(),
            'due_date' => $this->faker->dateTimeBetween('-1 week', '+1 week'),
        ];
    }
}
