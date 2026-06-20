<?php

use App\Models\Athlete;
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

    $this->coach = User::factory()->create();
    $this->coach->assignRole('coach');

    $this->event = Event::factory()->create(['status' => 'open']);
    $this->athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
        'age_category'        => 'Senior',
        'gender'              => 'M',
        'weight_category'     => '-68kg',
    ]);
});

// ── declare ───────────────────────────────────────────────────────────────────

it('technical staff can declare a weigh-in as passed', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", ['status' => 'passed'])
        ->assertOk()
        ->assertJson(['success' => true, 'status' => 'passed']);

    expect($this->athlete->fresh()->weigh_in_status)->toBe('passed');
});

it('technical staff can declare a weigh-in as failed', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", ['status' => 'failed'])
        ->assertOk()
        ->assertJson(['success' => true, 'status' => 'failed']);

    expect($this->athlete->fresh()->weigh_in_status)->toBe('failed');
});

it('records actual weight when provided', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", [
            'status'        => 'passed',
            'actual_weight' => 66.5,
        ])
        ->assertOk();

    expect($this->athlete->fresh()->weigh_in_weight)->toBe(66.5);
});

it('rejects invalid weigh-in status', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", ['status' => 'unknown'])
        ->assertUnprocessable();
});

it('rejects weigh-in without status field', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", [])
        ->assertUnprocessable();
});

it('coach cannot declare a weigh-in', function () {
    $this->actingAs($this->coach)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in", ['status' => 'passed'])
        ->assertForbidden();
});

// ── reset ─────────────────────────────────────────────────────────────────────

it('technical staff can reset a weigh-in', function () {
    $this->athlete->forceFill([
        'weigh_in_status' => 'passed',
        'weigh_in_weight' => 65.0,
        'weigh_in_by'     => $this->technical->id,
    ])->save();

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in/reset")
        ->assertOk()
        ->assertJson(['success' => true]);

    $fresh = $this->athlete->fresh();
    expect($fresh->weigh_in_status)->toBeNull();
    expect($fresh->weigh_in_weight)->toBeNull();
    expect($fresh->weigh_in_by)->toBeNull();
});

it('coach cannot reset a weigh-in', function () {
    $this->actingAs($this->coach)
        ->postJson("/api/athletes/{$this->athlete->id}/weigh-in/reset")
        ->assertForbidden();
});
