<?php

use App\Models\ChatMessage;
use App\Models\Event;
use App\Models\LiveBan;
use App\Models\LiveSession;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->event = Event::factory()->create();
    $this->live  = LiveSession::factory()->live()->create(['event_id' => $this->event->id]);
});

it('returns an empty chat history initially', function () {
    $this->getJson("/direct/{$this->live->id}/chat")
        ->assertOk()
        ->assertJson(['success' => true, 'data' => []]);
});

it('lets a visitor post a message during a live', function () {
    $this->postJson("/direct/{$this->live->id}/chat", [
        'pseudo'  => 'Moussa',
        'message' => 'Allez les rouges !',
    ])
        ->assertStatus(201)
        ->assertJson(['success' => true, 'data' => ['pseudo' => 'Moussa']]);

    $this->assertDatabaseHas('chat_messages', [
        'live_session_id' => $this->live->id,
        'pseudo'          => 'Moussa',
        'message'         => 'Allez les rouges !',
    ]);
});

it('rejects a message when the live is not running', function () {
    $ended = LiveSession::factory()->ended()->create(['event_id' => $this->event->id]);

    $this->postJson("/direct/{$ended->id}/chat", ['pseudo' => 'X', 'message' => 'hello'])
        ->assertStatus(422);
});

it('requires a pseudo and a message', function () {
    $this->postJson("/direct/{$this->live->id}/chat", ['pseudo' => '', 'message' => ''])
        ->assertStatus(422);
});

it('blocks a banned visitor (by pseudo)', function () {
    LiveBan::create(['live_session_id' => $this->live->id, 'pseudo' => 'Troll']);

    $this->postJson("/direct/{$this->live->id}/chat", ['pseudo' => 'Troll', 'message' => 'spam'])
        ->assertStatus(403);
});

it('history shows visible messages and hides deleted ones', function () {
    ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'A', 'message' => 'visible']);
    ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'B', 'message' => 'cache', 'is_deleted' => true]);

    $res = $this->getJson("/direct/{$this->live->id}/chat")->assertOk();

    expect(collect($res['data'])->pluck('message')->all())
        ->toContain('visible')
        ->not->toContain('cache');
});

it('throttles two messages sent back-to-back', function () {
    $this->postJson("/direct/{$this->live->id}/chat", ['pseudo' => 'Speedy', 'message' => 'msg1'])->assertStatus(201);
    $this->postJson("/direct/{$this->live->id}/chat", ['pseudo' => 'Speedy', 'message' => 'msg2'])->assertStatus(429);
});

it('strips html tags from pseudo and message', function () {
    $this->postJson("/direct/{$this->live->id}/chat", [
        'pseudo'  => '<b>Hacker</b>',
        'message' => 'hello <script>alert(1)</script>',
    ])->assertStatus(201);

    $this->assertDatabaseHas('chat_messages', [
        'pseudo'  => 'Hacker',
        'message' => 'hello alert(1)',
    ]);
});

// ── Réactions emoji ─────────────────────────────────────────────────────────────

it('accepts a valid reaction during a live', function () {
    $this->postJson("/direct/{$this->live->id}/reaction", ['emoji' => '🔥'])->assertOk();
});

it('rejects an invalid reaction emoji', function () {
    $this->postJson("/direct/{$this->live->id}/reaction", ['emoji' => '💩'])->assertStatus(422);
});

it('rejects reactions when the live is not running', function () {
    $ended = LiveSession::factory()->ended()->create(['event_id' => $this->event->id]);
    $this->postJson("/direct/{$ended->id}/reaction", ['emoji' => '🔥'])->assertStatus(422);
});
