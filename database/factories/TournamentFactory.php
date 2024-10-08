<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\TournamentType;
use App\Models\TournamentPlatform;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tournament>
 */
class TournamentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $end = $this->faker->time('H:i');
        return [
            'name' => $this->faker->sentence(4),
            'buy_in'=> rand(0,100),
            'date' => now()->addDays(rand(0,85)),
            'subscription_begin_at' => $this->faker->time('H:i',$end),
            'subscription_end_at' => $end,
            'is_approved'=> $this->faker->boolean(),

            'tournament_type_id' => TournamentType::all()->random()->id,
            'tournament_platform_id' => TournamentPlatform::all()->random()->id,
        ];
    }
}
