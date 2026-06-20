<?php

use App\Models\Event;
use App\Models\LiveSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'technical', 'coach', 'financial'] as $r) {
        Role::firstOrCreate(['name' => $r, 'guard_name' => 'web']);
    }
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->technical = User::factory()->create();
    $this->technical->assignRole('technical');

    $this->event = Event::factory()->create();
});

// ── Extraction de l'ID YouTube ─────────────────────────────────────────────────

it('extracts the youtube id from every url form', function () {
    expect(LiveSession::extractYoutubeId('dQw4w9WgXcQ'))->toBe('dQw4w9WgXcQ');
    expect(LiveSession::extractYoutubeId('https://www.youtube.com/watch?v=dQw4w9WgXcQ'))->toBe('dQw4w9WgXcQ');
    expect(LiveSession::extractYoutubeId('https://youtu.be/dQw4w9WgXcQ'))->toBe('dQw4w9WgXcQ');
    expect(LiveSession::extractYoutubeId('https://www.youtube.com/live/dQw4w9WgXcQ'))->toBe('dQw4w9WgXcQ');
    expect(LiveSession::extractYoutubeId('https://www.youtube.com/embed/dQw4w9WgXcQ'))->toBe('dQw4w9WgXcQ');
    expect(LiveSession::extractYoutubeId('ceci n\'est pas une url'))->toBeNull();
});

// ── Gestion (admin) ─────────────────────────────────────────────────────────────

it('admin can create a live from a youtube url', function () {
    $this->actingAs($this->admin)
        ->postJson('/api/live', [
            'event_id' => $this->event->id,
            'title'    => 'Finale Senior -68kg',
            'youtube'  => 'https://youtu.be/dQw4w9WgXcQ',
        ])
        ->assertStatus(201)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('live_sessions', [
        'title'            => 'Finale Senior -68kg',
        'youtube_video_id' => 'dQw4w9WgXcQ',
        'status'           => 'scheduled',
    ]);
});

it('rejects an invalid youtube link', function () {
    $this->actingAs($this->admin)
        ->postJson('/api/live', ['event_id' => $this->event->id, 'title' => 'X', 'youtube' => 'pas une url'])
        ->assertStatus(422);
});

it('a non-admin (technical) cannot manage lives', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/live', ['event_id' => $this->event->id, 'title' => 'X', 'youtube' => 'dQw4w9WgXcQ'])
        ->assertForbidden();
});

it('admin can start and stop a live', function () {
    $live = LiveSession::factory()->create(['event_id' => $this->event->id]);

    $this->actingAs($this->admin)->postJson("/api/live/{$live->id}/start")->assertOk();
    expect($live->fresh()->status)->toBe('live');
    expect($live->fresh()->started_at)->not->toBeNull();

    $this->actingAs($this->admin)->postJson("/api/live/{$live->id}/stop")->assertOk();
    expect($live->fresh()->status)->toBe('ended');
    expect($live->fresh()->ended_at)->not->toBeNull();
});

it('admin can delete a live', function () {
    $live = LiveSession::factory()->create(['event_id' => $this->event->id]);

    $this->actingAs($this->admin)->deleteJson("/api/live/{$live->id}")->assertOk();
    $this->assertDatabaseMissing('live_sessions', ['id' => $live->id]);
});

// ── Page publique de visionnage ─────────────────────────────────────────────────

it('the public can watch an ongoing live', function () {
    $live = LiveSession::factory()->live()->create(['event_id' => $this->event->id, 'title' => 'Direct Test']);

    $this->get("/direct/{$live->id}")
        ->assertOk()
        ->assertSee('Direct Test')
        ->assertSee('EN DIRECT');
});

it('a scheduled live is not publicly accessible yet', function () {
    $live = LiveSession::factory()->create(['event_id' => $this->event->id, 'status' => 'scheduled']);

    $this->get("/direct/{$live->id}")->assertNotFound();
});

it('an ended live stays available as a replay', function () {
    $live = LiveSession::factory()->ended()->create(['event_id' => $this->event->id]);

    $this->get("/direct/{$live->id}")
        ->assertOk()
        ->assertSee('REPLAY');
});

// ── Encart sur la page événement ────────────────────────────────────────────────

it('shows the live banner on the event page when a live is running', function () {
    $event = Event::factory()->create(['status' => 'ongoing']);
    LiveSession::factory()->live()->create(['event_id' => $event->id, 'title' => 'Combat en cours']);

    $this->get("/evenements/{$event->slug}")
        ->assertOk()
        ->assertSee('Combat en cours')
        ->assertSee('Regarder maintenant');
});
