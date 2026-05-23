<?php

use App\Models\Athlete;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'technical', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coach',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'financial', 'guard_name' => 'web']);

    $this->technical = User::factory()->create();
    $this->technical->assignRole('technical');

    $this->coach = User::factory()->create();
    $this->coach->assignRole('coach');

    $this->event = Event::factory()->create(['status' => 'open']);
});

// ── Index ────────────────────────────────────────────────────────────────────

it('technical staff can list athletes', function () {
    Athlete::factory(3)->create(['event_id' => $this->event->id]);

    $this->actingAs($this->technical)
        ->getJson('/api/athletes')
        ->assertOk()
        ->assertJsonStructure(['success', 'data']);
});

it('coach sees only own athletes', function () {
    Athlete::factory(2)->create(['event_id' => $this->event->id, 'coach_id' => $this->coach->id]);
    Athlete::factory(3)->create(['event_id' => $this->event->id]);

    $response = $this->actingAs($this->coach)
        ->getJson('/api/athletes')
        ->assertOk();

    expect($response['data'])->toHaveCount(2);
});

// ── Store ────────────────────────────────────────────────────────────────────

it('coach can register an athlete', function () {
    $payload = [
        'first_name'  => 'Test',
        'last_name'   => 'Athlete',
        'birth_date'  => '2000-01-01',
        'gender'      => 'M',
        'weight'      => 68.0,
        'club'        => 'Test Club',
        'event_id'    => $this->event->id,
        'nationality' => 'Sénégalais',
    ];

    $this->actingAs($this->coach)
        ->postJson('/api/athletes', $payload)
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('athletes', [
        'first_name' => 'Test',
        'last_name'  => 'Athlete',
        'coach_id'   => $this->coach->id,
    ]);
});

it('rejects athlete registration with missing required fields', function () {
    $this->actingAs($this->coach)
        ->postJson('/api/athletes', ['first_name' => 'Test'])
        ->assertUnprocessable();
});

// ── Validate ─────────────────────────────────────────────────────────────────

it('technical staff can validate an athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$athlete->id}/validate")
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($athlete->fresh()->registration_status)->toBe('validated');
});

it('coach cannot validate athletes', function () {
    $athlete = Athlete::factory()->create(['event_id' => $this->event->id]);

    $this->actingAs($this->coach)
        ->postJson("/api/athletes/{$athlete->id}/validate")
        ->assertForbidden();
});

// ── Delete ───────────────────────────────────────────────────────────────────

it('technical staff can delete an athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->technical)
        ->deleteJson("/api/athletes/{$athlete->id}")
        ->assertOk();

    $this->assertSoftDeleted('athletes', ['id' => $athlete->id]);
});

// ── Security rules ────────────────────────────────────────────────────────────

it('cannot delete a validated athlete', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
    ]);

    $this->actingAs($this->technical)
        ->deleteJson("/api/athletes/{$athlete->id}")
        ->assertStatus(422);

    $this->assertDatabaseHas('athletes', ['id' => $athlete->id]);
});

it('bulk delete skips validated athletes silently', function () {
    $validated = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
    ]);
    $pending = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->technical)
        ->postJson('/api/athletes/bulk-delete', ['ids' => [$validated->id, $pending->id]])
        ->assertOk()
        ->assertJsonFragment(['deleted' => 1]);

    $this->assertDatabaseHas('athletes', ['id' => $validated->id]);
    $this->assertSoftDeleted('athletes', ['id' => $pending->id]);
});

it('validation fails when weight_category is missing', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'pending',
        'weight_category'     => null,
    ]);

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$athlete->id}/validate")
        ->assertStatus(422);

    expect($athlete->fresh()->registration_status)->toBe('pending');
});

it('coach cannot register athlete for closed event', function () {
    $closed = Event::factory()->create(['status' => 'closed']);

    $this->actingAs($this->coach)
        ->postJson('/api/athletes', [
            'first_name' => 'Test',
            'last_name'  => 'Athlete',
            'birth_date' => '2005-01-01',
            'gender'     => 'M',
            'club'       => 'Club',
            'event_id'   => $closed->id,
        ])
        ->assertStatus(422);
});

// ── Bulk validate ─────────────────────────────────────────────────────────────

it('technical staff can bulk validate athletes', function () {
    $athletes = Athlete::factory(3)->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'pending',
    ]);
    $ids = $athletes->pluck('id')->toArray();

    $this->actingAs($this->technical)
        ->postJson('/api/athletes/bulk-validate', ['ids' => $ids])
        ->assertOk()
        ->assertJson(['success' => true]);

    foreach ($ids as $id) {
        expect(Athlete::find($id)->registration_status)->toBe('validated');
    }
});
