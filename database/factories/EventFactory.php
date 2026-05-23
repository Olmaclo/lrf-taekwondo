<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition(): array
    {
        $name = $this->faker->words(4, true);
        return [
            'name'             => ucwords($name),
            'slug'             => Str::slug($name) . '-' . $this->faker->unique()->numberBetween(1000, 9999),
            'type'             => $this->faker->randomElement(['kyorugi', 'poomsae', 'mixed']),
            'status'           => $this->faker->randomElement(['upcoming', 'open', 'closed']),
            'start_date'       => $this->faker->dateTimeBetween('now', '+6 months')->format('Y-m-d'),
            'end_date'         => $this->faker->dateTimeBetween('+6 months', '+7 months')->format('Y-m-d'),
            'location'         => $this->faker->city() . ', Sénégal',
            'registration_fee' => $this->faker->randomElement([3000, 5000, 8000, 10000]),
            'description'      => $this->faker->sentence(),
        ];
    }

    public function open(): self
    {
        return $this->state(['status' => 'open']);
    }

    public function upcoming(): self
    {
        return $this->state(['status' => 'upcoming']);
    }
}
