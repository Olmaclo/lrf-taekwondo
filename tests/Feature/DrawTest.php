<?php

use App\Models\Athlete;
use App\Models\Draw;
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

// ── Helper: create a Draw record with a 2-athlete match ───────────────────────

function makeDrawWithAthletes(Event $event, User $generator): array
{
    $athletes = Athlete::factory(2)->create([
        'event_id'            => $event->id,
        'registration_status' => 'validated',
    ]);

    $draw = Draw::create([
        'event_id'        => $event->id,
        'category'        => 'Senior Homme -68kg',
        'age_category'    => 'Senior',
        'gender'          => 'M',
        'weight_category' => '-68kg',
        'total_athletes'  => 2,
        'use_pools'       => false,
        'matches'         => [
            [
                'id'        => 1,
                'round'     => 1,
                'position'  => 1,
                'athlete1'  => ['id' => $athletes[0]->id, 'name' => $athletes[0]->full_name, 'club' => $athletes[0]->club],
                'athlete2'  => ['id' => $athletes[1]->id, 'name' => $athletes[1]->full_name, 'club' => $athletes[1]->club],
                'winner'    => null,
                'winner_id' => null,
                'is_bye'    => false,
                'pool'      => null,
            ],
        ],
        'generated_by'    => $generator->id,
        'generated_at'    => now(),
    ]);

    return [$draw, $athletes];
}

// ── generate ──────────────────────────────────────────────────────────────────

it('technical staff can generate a draw', function () {
    Athlete::factory(3)->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
        'age_category'        => 'Senior',
        'gender'              => 'M',
        'weight_category'     => '-68kg',
    ]);

    $this->actingAs($this->technical)
        ->postJson('/api/draws/generate', [
            'event_id'        => $this->event->id,
            'age_category'    => 'Senior',
            'gender'          => 'M',
            'weight_category' => '-68kg',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseHas('draws', [
        'event_id'        => $this->event->id,
        'age_category'    => 'Senior',
        'gender'          => 'M',
        'weight_category' => '-68kg',
    ]);
});

it('fails to generate a draw with fewer than 2 athletes', function () {
    Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
        'age_category'        => 'Junior',
        'gender'              => 'F',
        'weight_category'     => '-49kg',
    ]);

    $this->actingAs($this->technical)
        ->postJson('/api/draws/generate', [
            'event_id'        => $this->event->id,
            'age_category'    => 'Junior',
            'gender'          => 'F',
            'weight_category' => '-49kg',
        ])
        ->assertStatus(422)
        ->assertJson(['success' => false]);
});

it('coach cannot generate a draw', function () {
    $this->actingAs($this->coach)
        ->postJson('/api/draws/generate', [
            'event_id'        => $this->event->id,
            'age_category'    => 'Senior',
            'gender'          => 'M',
            'weight_category' => '-68kg',
        ])
        ->assertForbidden();
});

it('generate rejects invalid age_category', function () {
    $this->actingAs($this->technical)
        ->postJson('/api/draws/generate', [
            'event_id'        => $this->event->id,
            'age_category'    => 'Benjamin',
            'gender'          => 'M',
            'weight_category' => '-68kg',
        ])
        ->assertUnprocessable();
});

// ── byEvent ───────────────────────────────────────────────────────────────────

it('technical staff can list draws by event', function () {
    [$draw] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->technical)
        ->getJson("/api/draws/by-event?event_id={$this->event->id}")
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('returns empty list when no draws exist for event', function () {
    $other = Event::factory()->create();

    $this->actingAs($this->technical)
        ->getJson("/api/draws/by-event?event_id={$other->id}")
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

// ── show ──────────────────────────────────────────────────────────────────────

it('technical staff can view a draw', function () {
    [$draw] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->technical)
        ->getJson("/api/draws/{$draw->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $draw->id)
        ->assertJsonStructure(['data' => ['id', 'category', 'matches', 'total_athletes']]);
});

// ── setWinner ─────────────────────────────────────────────────────────────────

it('technical staff can set a match winner', function () {
    [$draw, $athletes] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->technical)
        ->postJson("/api/draws/{$draw->id}/set-winner", [
            'match_id'   => 1,
            'athlete_id' => $athletes[0]->id,
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $draw->refresh();
    expect($draw->matches[0]['winner_id'])->toBe($athletes[0]->id);
    expect($draw->matches[0]['winner']['id'])->toBe($athletes[0]->id);
});

it('coach cannot set a match winner', function () {
    [$draw, $athletes] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->coach)
        ->postJson("/api/draws/{$draw->id}/set-winner", [
            'match_id'   => 1,
            'athlete_id' => $athletes[0]->id,
        ])
        ->assertForbidden();
});

// ── resetWinner ───────────────────────────────────────────────────────────────

it('technical staff can reset a match winner', function () {
    [$draw, $athletes] = makeDrawWithAthletes($this->event, $this->technical);

    // Set winner first
    $matches = $draw->matches;
    $matches[0]['winner']    = ['id' => $athletes[0]->id];
    $matches[0]['winner_id'] = $athletes[0]->id;
    $draw->matches = $matches;
    $draw->save();

    $this->actingAs($this->technical)
        ->postJson("/api/draws/{$draw->id}/reset-winner", ['match_id' => 1])
        ->assertOk()
        ->assertJson(['success' => true]);

    $draw->refresh();
    expect($draw->matches[0]['winner'])->toBeNull();
});

// ── destroy ───────────────────────────────────────────────────────────────────

it('technical staff can delete a draw', function () {
    [$draw] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->technical)
        ->deleteJson("/api/draws/{$draw->id}")
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->assertDatabaseMissing('draws', ['id' => $draw->id]);
});

it('coach cannot delete a draw', function () {
    [$draw] = makeDrawWithAthletes($this->event, $this->technical);

    $this->actingAs($this->coach)
        ->deleteJson("/api/draws/{$draw->id}")
        ->assertForbidden();
});
