<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'technical', 'coach', 'financial'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }

    $this->technical = User::factory()->create();
    $this->technical->assignRole('technical');

    $this->financial = User::factory()->create();
    $this->financial->assignRole('financial');

    $this->coach = User::factory()->create();
    $this->coach->assignRole('coach');

    $this->event = Event::factory()->create(['status' => 'open']);
});

// ── index ─────────────────────────────────────────────────────────────────────

it('technical staff can list events', function () {
    $this->actingAs($this->technical)
        ->getJson('/api/events')
        ->assertOk()
        ->assertJsonStructure(['success', 'data']);
});

it('financial staff can list events', function () {
    $this->actingAs($this->financial)
        ->getJson('/api/events')
        ->assertOk();
});

it('coach cannot list events', function () {
    $this->actingAs($this->coach)
        ->getJson('/api/events')
        ->assertForbidden();
});

// ── show ──────────────────────────────────────────────────────────────────────

it('technical staff can view an event', function () {
    $this->actingAs($this->technical)
        ->getJson("/api/events/{$this->event->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $this->event->id);
});

it('financial staff can view an event', function () {
    $this->actingAs($this->financial)
        ->getJson("/api/events/{$this->event->id}")
        ->assertOk();
});

// ── store ─────────────────────────────────────────────────────────────────────

it('technical staff can create an event', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/events', [
            'name'       => 'Championnat National Sénégal',
            'type'       => 'kyorugi',
            'start_date' => '2027-03-15',
            'end_date'   => '2027-03-16',
            'location'   => 'Dakar, Sénégal',
            'status'     => 'upcoming',
        ])
        ->assertStatus(201)
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('events', ['name' => 'Championnat National Sénégal']);
});

it('coach cannot create an event', function () {
    $this->actingAs($this->coach)
        ->postJson('/api/events', [
            'name'       => 'Test',
            'type'       => 'kyorugi',
            'start_date' => '2027-03-15',
        ])
        ->assertForbidden();
});

it('event creation fails with invalid type', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/events', [
            'name'       => 'Invalid Type',
            'type'       => 'invalid_type',
            'start_date' => '2027-03-15',
        ])
        ->assertUnprocessable();
});

it('event creation fails when end_date is before start_date', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/events', [
            'name'       => 'Bad Dates',
            'type'       => 'kyorugi',
            'start_date' => '2027-03-15',
            'end_date'   => '2027-03-10',
        ])
        ->assertUnprocessable();
});

it('event creation requires name, type and start_date', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/events', [])
        ->assertUnprocessable();
});

// ── update ────────────────────────────────────────────────────────────────────

it('technical staff can update an event', function () {
    $this->actingAs($this->technical)
        ->putJson("/api/events/{$this->event->id}", ['location' => 'Thiès, Sénégal'])
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->event->fresh()->location)->toBe('Thiès, Sénégal');
});

it('technical staff can change event status', function () {
    $this->actingAs($this->technical)
        ->putJson("/api/events/{$this->event->id}", ['status' => 'closed'])
        ->assertOk();

    expect($this->event->fresh()->status)->toBe('closed');
});

it('coach cannot update an event', function () {
    $this->actingAs($this->coach)
        ->putJson("/api/events/{$this->event->id}", ['location' => 'Thiès'])
        ->assertForbidden();
});

// ── destroy ───────────────────────────────────────────────────────────────────

it('technical staff can delete an event', function () {
    $event = Event::factory()->create();

    $this->actingAs($this->technical)
        ->deleteJson("/api/events/{$event->id}")
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted('events', ['id' => $event->id]);
});

it('coach cannot delete an event', function () {
    $this->actingAs($this->coach)
        ->deleteJson("/api/events/{$this->event->id}")
        ->assertForbidden();
});
