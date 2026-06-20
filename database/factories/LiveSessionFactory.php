<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\LiveSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class LiveSessionFactory extends Factory
{
    protected $model = LiveSession::class;

    public function definition(): array
    {
        return [
            'event_id'         => Event::factory(),
            'title'            => 'Direct — ' . ucwords($this->faker->words(3, true)),
            'youtube_video_id' => $this->faker->regexify('[A-Za-z0-9_-]{11}'),
            'status'           => 'scheduled',
            'description'      => $this->faker->optional()->sentence(),
            'created_by'       => null,
        ];
    }

    public function live(): self
    {
        return $this->state(['status' => 'live', 'started_at' => now()]);
    }

    public function ended(): self
    {
        return $this->state([
            'status'     => 'ended',
            'started_at' => now()->subHour(),
            'ended_at'   => now(),
        ]);
    }
}
