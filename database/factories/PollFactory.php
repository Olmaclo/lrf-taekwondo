<?php

namespace Database\Factories;

use App\Models\LiveSession;
use App\Models\Poll;
use Illuminate\Database\Eloquent\Factories\Factory;

class PollFactory extends Factory
{
    protected $model = Poll::class;

    public function definition(): array
    {
        return [
            'live_session_id' => LiveSession::factory(),
            'question'        => 'Qui va gagner ce combat ?',
            'options'         => ['Coin rouge', 'Coin bleu'],
            'status'          => 'active',
            'created_by'      => null,
        ];
    }

    public function closed(): self
    {
        return $this->state(['status' => 'closed']);
    }
}
