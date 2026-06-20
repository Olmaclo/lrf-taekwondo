<?php

use App\Models\ChatMessage;
use App\Models\Event;
use App\Models\LiveSession;
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

    $this->moderator = User::factory()->create();
    $this->moderator->givePermissionTo('moderate-live');

    $this->event = Event::factory()->create();
    $this->live  = LiveSession::factory()->live()->create(['event_id' => $this->event->id]);
});

// ── Qui peut modérer ────────────────────────────────────────────────────────────

it('an admin can moderate by default', function () {
    expect($this->admin->canModerateLive())->toBeTrue();
});

it('a designated moderator can moderate', function () {
    expect($this->moderator->canModerateLive())->toBeTrue();
});

it('a regular coach cannot moderate', function () {
    expect($this->coach->canModerateLive())->toBeFalse();
});

// ── Suppression de message ──────────────────────────────────────────────────────

it('a moderator can delete a message', function () {
    $msg = ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'X', 'message' => 'spam']);

    $this->actingAs($this->moderator)
        ->postJson("/direct/{$this->live->id}/messages/{$msg->id}/delete")
        ->assertOk();

    expect($msg->fresh()->is_deleted)->toBeTrue();
});

it('a non-moderator cannot delete a message', function () {
    $msg = ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'X', 'message' => 'spam']);

    $this->actingAs($this->coach)
        ->postJson("/direct/{$this->live->id}/messages/{$msg->id}/delete")
        ->assertForbidden();
});

// ── Bannissement ────────────────────────────────────────────────────────────────

it('banning an author purges all their messages and records the ban', function () {
    $m1 = ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'Troll', 'message' => 'spam1', 'ip_hash' => 'abc']);
    $m2 = ChatMessage::create(['live_session_id' => $this->live->id, 'pseudo' => 'Troll', 'message' => 'spam2', 'ip_hash' => 'abc']);

    $this->actingAs($this->moderator)
        ->postJson("/direct/{$this->live->id}/messages/{$m1->id}/ban")
        ->assertOk();

    expect($m1->fresh()->is_deleted)->toBeTrue();
    expect($m2->fresh()->is_deleted)->toBeTrue();
    $this->assertDatabaseHas('live_bans', ['live_session_id' => $this->live->id, 'pseudo' => 'Troll']);
});

// ── Gestion des modérateurs ─────────────────────────────────────────────────────

it('an admin can promote and demote a moderator', function () {
    $this->actingAs($this->admin)
        ->postJson("/api/live/moderators/{$this->coach->id}/toggle")
        ->assertOk()
        ->assertJson(['is_moderator' => true]);

    expect($this->coach->fresh()->canModerateLive())->toBeTrue();

    $this->actingAs($this->admin)
        ->postJson("/api/live/moderators/{$this->coach->id}/toggle")
        ->assertOk()
        ->assertJson(['is_moderator' => false]);

    expect($this->coach->fresh()->canModerateLive())->toBeFalse();
});

it('lists users for moderator management', function () {
    $this->actingAs($this->admin)
        ->getJson('/api/live/moderators')
        ->assertOk()
        ->assertJsonStructure(['data' => [['id', 'name', 'is_admin', 'is_moderator']]]);
});

it('a regular coach cannot access moderator management', function () {
    $this->actingAs($this->coach)
        ->getJson('/api/live/moderators')
        ->assertForbidden();
});
