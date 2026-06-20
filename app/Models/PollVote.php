<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PollVote extends Model
{
    protected $fillable = ['poll_id', 'option_index', 'voter_hash'];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }
}
