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
});

// ── index ─────────────────────────────────────────────────────────────────────

it('technical staff can list coaches', function () {
    $this->actingAs($this->technical)
        ->getJson('/api/coaches')
        ->assertOk()
        ->assertJsonStructure(['success', 'data']);
});

it('coach cannot list coaches', function () {
    $this->actingAs($this->coach)
        ->getJson('/api/coaches')
        ->assertForbidden();
});

// ── show ──────────────────────────────────────────────────────────────────────

it('technical staff can view a coach profile', function () {
    $this->actingAs($this->technical)
        ->getJson("/api/coaches/{$this->coach->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $this->coach->id)
        ->assertJsonStructure(['data' => ['id', 'name', 'email', 'athletes_count']]);
});

// ── validate / reject ─────────────────────────────────────────────────────────

it('technical staff can validate a coach', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/coaches/{$this->coach->id}/validate")
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->coach->fresh()->is_validated)->toBeTrue();
    expect($this->coach->fresh()->account_status)->toBe('approved');
});

it('technical staff can reject a coach', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/coaches/{$this->coach->id}/reject")
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->coach->fresh()->is_validated)->toBeFalse();
    expect($this->coach->fresh()->account_status)->toBe('rejected');
});

// ── bulk validate / reject ────────────────────────────────────────────────────

it('technical staff can bulk validate coaches', function () {
    $coaches = User::factory(2)->create();
    $coaches->each(fn ($c) => $c->assignRole('coach'));
    $ids = $coaches->pluck('id')->toArray();

    $this->actingAs($this->technical)
        ->postJson('/api/coaches/bulk-validate', ['ids' => $ids])
        ->assertOk()
        ->assertJson(['success' => true]);

    foreach ($ids as $id) {
        expect(User::find($id)->is_validated)->toBeTrue();
        expect(User::find($id)->account_status)->toBe('approved');
    }
});

it('technical staff can bulk reject coaches', function () {
    $coaches = User::factory(2)->create();
    $coaches->each(fn ($c) => $c->assignRole('coach'));
    $ids = $coaches->pluck('id')->toArray();

    $this->actingAs($this->technical)
        ->postJson('/api/coaches/bulk-reject', ['ids' => $ids])
        ->assertOk()
        ->assertJson(['success' => true]);

    foreach ($ids as $id) {
        expect(User::find($id)->is_validated)->toBeFalse();
        expect(User::find($id)->account_status)->toBe('rejected');
    }
});

// ── destroy ───────────────────────────────────────────────────────────────────

it('technical staff can delete another coach', function () {
    $other = User::factory()->create();
    $other->assignRole('coach');

    $this->actingAs($this->technical)
        ->deleteJson("/api/coaches/{$other->id}")
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('users', ['id' => $other->id]);
});

it('technical staff cannot delete their own account', function () {
    $this->actingAs($this->technical)
        ->deleteJson("/api/coaches/{$this->technical->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('users', ['id' => $this->technical->id]);
});

// ── unregisterAthlete ─────────────────────────────────────────────────────────

it('coach can unregister their own pending athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $this->coach->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->coach)
        ->deleteJson("/api/coaches/athletes/{$athlete->id}/unregister")
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertSoftDeleted('athletes', ['id' => $athlete->id]);
});

it('coach cannot unregister a validated athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $this->coach->id,
        'registration_status' => 'validated',
    ]);

    $this->actingAs($this->coach)
        ->deleteJson("/api/coaches/athletes/{$athlete->id}/unregister")
        ->assertStatus(422);

    $this->assertDatabaseHas('athletes', ['id' => $athlete->id, 'deleted_at' => null]);
});

it('coach cannot unregister another coach\'s athlete', function () {
    $otherCoach = User::factory()->create();
    $otherCoach->assignRole('coach');

    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $otherCoach->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->coach)
        ->deleteJson("/api/coaches/athletes/{$athlete->id}/unregister")
        ->assertForbidden();
});

it('technical staff can unregister any pending athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $this->coach->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->technical)
        ->deleteJson("/api/coaches/athletes/{$athlete->id}/unregister")
        ->assertOk();

    $this->assertSoftDeleted('athletes', ['id' => $athlete->id]);
});
