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

    $this->financial = User::factory()->create();
    $this->financial->assignRole('financial');

    // admin = à la fois technique et financier → garde l'override
    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

// ── Model: isLocked + scopes ───────────────────────────────────────────────────

it('marks finished and cancelled events as locked', function () {
    expect(Event::factory()->make(['status' => 'finished'])->isLocked())->toBeTrue();
    expect(Event::factory()->make(['status' => 'cancelled'])->isLocked())->toBeTrue();
    expect(Event::factory()->make(['status' => 'open'])->isLocked())->toBeFalse();
    expect(Event::factory()->make(['status' => 'upcoming'])->isLocked())->toBeFalse();
    expect(Event::factory()->make(['status' => 'ongoing'])->isLocked())->toBeFalse();
});

it('active and archived scopes split events correctly', function () {
    Event::factory()->create(['status' => 'open']);
    Event::factory()->create(['status' => 'ongoing']);
    Event::factory()->create(['status' => 'finished']);
    Event::factory()->create(['status' => 'cancelled']);

    expect(Event::active()->count())->toBe(2);
    expect(Event::archived()->count())->toBe(2);
});

// ── Brique 1: write-lock on locked events ──────────────────────────────────────

it('blocks a financial user from recording a payment on a finished event', function () {
    $event   = Event::factory()->create(['status' => 'finished']);
    $athlete = Athlete::factory()->create(['event_id' => $event->id, 'payment_status' => 'unpaid']);

    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$athlete->id}/payment", ['amount' => 5000])
        ->assertStatus(422);

    expect($athlete->fresh()->payment_status)->toBe('unpaid');
});

it('blocks a financial user from editing a payment on a cancelled event', function () {
    $event   = Event::factory()->create(['status' => 'cancelled']);
    $athlete = Athlete::factory()->create(['event_id' => $event->id, 'payment_status' => 'temp_validated']);

    $this->actingAs($this->financial)
        ->putJson("/api/financial/athletes/{$athlete->id}/payment", ['amount' => 9000, 'status' => 'validated'])
        ->assertStatus(422);
});

it('blocks a financial user from definitively validating on a finished event', function () {
    $event   = Event::factory()->create(['status' => 'finished']);
    $athlete = Athlete::factory()->create(['event_id' => $event->id, 'payment_status' => 'temp_validated']);

    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$athlete->id}/validate")
        ->assertStatus(422);

    expect($athlete->fresh()->payment_status)->toBe('temp_validated');
});

it('still allows payments on an active event', function () {
    $event   = Event::factory()->create(['status' => 'open']);
    $athlete = Athlete::factory()->create(['event_id' => $event->id, 'payment_status' => 'unpaid']);

    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$athlete->id}/payment", ['amount' => 5000])
        ->assertOk();

    expect($athlete->fresh()->payment_status)->toBe('temp_validated');
});

// ── Override: technical (admin) keeps the hand ─────────────────────────────────

it('lets an admin override the lock and record a payment on a finished event', function () {
    $event   = Event::factory()->create(['status' => 'finished']);
    $athlete = Athlete::factory()->create(['event_id' => $event->id, 'payment_status' => 'unpaid']);

    $this->actingAs($this->admin)
        ->postJson("/api/financial/athletes/{$athlete->id}/payment", ['amount' => 5000])
        ->assertOk();

    expect($athlete->fresh()->payment_status)->toBe('temp_validated');
});

// ── Bulk: locked events are skipped for a financial user ───────────────────────

it('bulk definitive validate skips athletes from locked events for a financial user', function () {
    $activeEvent   = Event::factory()->create(['status' => 'open']);
    $finishedEvent = Event::factory()->create(['status' => 'finished']);

    $onActive = Athlete::factory()->create([
        'event_id'            => $activeEvent->id,
        'registration_status' => 'validated',
        'payment_status'      => 'temp_validated',
    ]);
    $onFinished = Athlete::factory()->create([
        'event_id'            => $finishedEvent->id,
        'registration_status' => 'validated',
        'payment_status'      => 'temp_validated',
    ]);

    $this->actingAs($this->financial)
        ->postJson('/api/financial/bulk-validate', ['ids' => [$onActive->id, $onFinished->id]])
        ->assertOk()
        ->assertJsonFragment(['updated' => 1]);

    expect($onActive->fresh()->payment_status)->toBe('validated');
    expect($onFinished->fresh()->payment_status)->toBe('temp_validated');
});

it('bulk definitive validate processes locked events when an admin runs it', function () {
    $finishedEvent = Event::factory()->create(['status' => 'finished']);
    $athlete = Athlete::factory()->create([
        'event_id'            => $finishedEvent->id,
        'registration_status' => 'validated',
        'payment_status'      => 'temp_validated',
    ]);

    $this->actingAs($this->admin)
        ->postJson('/api/financial/bulk-validate', ['ids' => [$athlete->id]])
        ->assertOk()
        ->assertJsonFragment(['updated' => 1]);

    expect($athlete->fresh()->payment_status)->toBe('validated');
});

// ── Brique 2: auto-finish command ──────────────────────────────────────────────

it('auto-finishes events whose end_date is past', function () {
    $past = Event::factory()->create([
        'status'     => 'closed',
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date'   => now()->subDays(3)->format('Y-m-d'),
    ]);

    $this->artisan('events:auto-finish')->assertSuccessful();

    expect($past->fresh()->status)->toBe('finished');
});

it('auto-finishes events with no end_date whose start_date is past', function () {
    $past = Event::factory()->create([
        'status'     => 'ongoing',
        'start_date' => now()->subDays(2)->format('Y-m-d'),
        'end_date'   => null,
    ]);

    $this->artisan('events:auto-finish')->assertSuccessful();

    expect($past->fresh()->status)->toBe('finished');
});

it('does not auto-finish upcoming or ongoing events still in the future', function () {
    $future = Event::factory()->create([
        'status'     => 'open',
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date'   => now()->addDays(6)->format('Y-m-d'),
    ]);

    $this->artisan('events:auto-finish')->assertSuccessful();

    expect($future->fresh()->status)->toBe('open');
});

it('does not touch already finished or cancelled events', function () {
    $cancelled = Event::factory()->create([
        'status'     => 'cancelled',
        'start_date' => now()->subDays(5)->format('Y-m-d'),
        'end_date'   => now()->subDays(3)->format('Y-m-d'),
    ]);

    $this->artisan('events:auto-finish')->assertSuccessful();

    // Reste 'cancelled' — la commande ne le réécrit pas en 'finished'
    expect($cancelled->fresh()->status)->toBe('cancelled');
});

it('reports when there is nothing to finish', function () {
    Event::factory()->create([
        'status'     => 'open',
        'start_date' => now()->addDays(5)->format('Y-m-d'),
        'end_date'   => now()->addDays(6)->format('Y-m-d'),
    ]);

    $this->artisan('events:auto-finish')
        ->expectsOutputToContain('Aucun événement à clôturer.')
        ->assertSuccessful();
});
