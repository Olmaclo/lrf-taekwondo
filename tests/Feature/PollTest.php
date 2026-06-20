<?php

use App\Models\Event;
use App\Models\LiveSession;
use App\Models\Poll;
use App\Models\PollVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'technical', 'coach', 'financial'] as $r) {
        Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
    }
    Permission::firstOrCreate(['name' => 'moderate-live', 'guard_name' => 'web']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->coach = User::factory()->create();
    $this->coach->assignRole('coach');

    $this->event = Event::factory()->create();
    $this->live  = LiveSession::factory()->live()->create(['event_id' => $this->event->id]);
});

it('a moderator can start a poll', function () {
    $this->actingAs($this->admin)
        ->postJson("/api/live/{$this->live->id}/polls", ['question' => 'Qui gagne ?', 'options' => ['Rouge', 'Bleu']])
        ->assertStatus(201)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('polls', ['live_session_id' => $this->live->id, 'question' => 'Qui gagne ?', 'status' => 'active']);
});

it('a non-moderator cannot start a poll', function () {
    $this->actingAs($this->coach)
        ->postJson("/api/live/{$this->live->id}/polls", ['question' => 'X', 'options' => ['A', 'B']])
        ->assertForbidden();
});

it('a poll requires at least two options', function () {
    $this->actingAs($this->admin)
        ->postJson("/api/live/{$this->live->id}/polls", ['question' => 'X', 'options' => ['A']])
        ->assertStatus(422);
});

it('a visitor can vote', function () {
    $poll = Poll::factory()->create(['live_session_id' => $this->live->id, 'options' => ['A', 'B']]);

    $this->postJson("/direct/{$this->live->id}/polls/{$poll->id}/vote", ['option_index' => 0])
        ->assertOk()
        ->assertJson(['success' => true, 'voted' => 0]);

    $this->assertDatabaseHas('poll_votes', ['poll_id' => $poll->id, 'option_index' => 0]);
});

it('prevents double voting', function () {
    $poll = Poll::factory()->create(['live_session_id' => $this->live->id, 'options' => ['A', 'B']]);

    $this->postJson("/direct/{$this->live->id}/polls/{$poll->id}/vote", ['option_index' => 0])->assertOk();
    $this->postJson("/direct/{$this->live->id}/polls/{$poll->id}/vote", ['option_index' => 1])->assertStatus(422);
});

it('cannot vote on a closed poll', function () {
    $poll = Poll::factory()->closed()->create(['live_session_id' => $this->live->id, 'options' => ['A', 'B']]);

    $this->postJson("/direct/{$this->live->id}/polls/{$poll->id}/vote", ['option_index' => 0])->assertStatus(422);
});

it('a moderator can close a poll', function () {
    $poll = Poll::factory()->create(['live_session_id' => $this->live->id]);

    $this->actingAs($this->admin)->postJson("/api/live/polls/{$poll->id}/close")->assertOk();
    expect($poll->fresh()->status)->toBe('closed');
});

it('starting a new poll closes the previous one', function () {
    $old = Poll::factory()->create(['live_session_id' => $this->live->id]);

    $this->actingAs($this->admin)
        ->postJson("/api/live/{$this->live->id}/polls", ['question' => 'Nouveau ?', 'options' => ['A', 'B']])
        ->assertStatus(201);

    expect($old->fresh()->status)->toBe('closed');
});

it('returns the active poll with its results', function () {
    $poll = Poll::factory()->create(['live_session_id' => $this->live->id, 'options' => ['A', 'B']]);
    PollVote::create(['poll_id' => $poll->id, 'option_index' => 0, 'voter_hash' => 'xxx']);

    $res = $this->getJson("/direct/{$this->live->id}/poll")->assertOk();

    expect($res['data']['total'])->toBe(1);
    expect($res['data']['question'])->toBe($poll->question);
});
