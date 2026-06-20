<?php

use App\Models\Athlete;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'technical', 'financial', 'coach'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }

    $this->financial = User::factory()->create();
    $this->financial->assignRole('financial');

    $this->technical = User::factory()->create();
    $this->technical->assignRole('technical');

    $this->event   = Event::factory()->create(['registration_fee' => 5000]);
    $this->athlete = Athlete::factory()->create([
        'event_id'       => $this->event->id,
        'payment_status' => 'unpaid',
    ]);
});

it('financial staff can record a payment', function () {
    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/payment", [
            'amount'         => 5000,
            'payment_method' => 'cash',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->athlete->refresh();
    expect($this->athlete->payment_status)->toBe('temp_validated');
    expect($this->athlete->payment_amount)->toBe(5000.0);
});

it('financial staff can definitively validate a payment', function () {
    $this->athlete->forceFill([
        'payment_status' => 'temp_validated',
        'payment_amount' => 5000,
    ])->save();

    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/validate")
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->athlete->fresh()->payment_status)->toBe('validated');
});

it('generates a receipt for paid athlete', function () {
    $this->athlete->forceFill([
        'payment_status' => 'validated',
        'payment_amount' => 5000,
        'receipt_number' => 'REC-000001',
    ])->save();

    $this->actingAs($this->financial)
        ->get("/api/financial/athletes/{$this->athlete->id}/receipt")
        ->assertOk()
        ->assertHeader('content-type', 'application/pdf');
});

it('technical staff cannot record payments', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/payment", ['amount' => 5000])
        ->assertForbidden();
});

it('bulk temp validate works for validated athletes', function () {
    $athletes = Athlete::factory(3)->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
        'payment_status'      => 'unpaid',
    ]);
    $ids = $athletes->pluck('id')->toArray();

    $this->actingAs($this->financial)
        ->postJson('/api/financial/bulk-temp-validate', ['ids' => $ids])
        ->assertOk()
        ->assertJson(['success' => true]);

    foreach ($ids as $id) {
        expect(Athlete::find($id)->payment_status)->toBe('temp_validated');
    }
});

it('bulk temp validate skips non-validated athletes', function () {
    $pending   = Athlete::factory()->create(['event_id' => $this->event->id, 'registration_status' => 'pending',   'payment_status' => 'unpaid']);
    $validated = Athlete::factory()->create(['event_id' => $this->event->id, 'registration_status' => 'validated', 'payment_status' => 'unpaid']);

    $this->actingAs($this->financial)
        ->postJson('/api/financial/bulk-temp-validate', ['ids' => [$pending->id, $validated->id]])
        ->assertOk()
        ->assertJsonFragment(['updated' => 1]);

    expect($pending->fresh()->payment_status)->toBe('unpaid');
    expect($validated->fresh()->payment_status)->toBe('temp_validated');
});

// ── editPayment ───────────────────────────────────────────────────────────────

it('financial staff can edit a payment amount and status', function () {
    $this->athlete->forceFill([
        'payment_status' => 'temp_validated',
        'payment_amount' => 3000,
    ])->save();

    $this->actingAs($this->financial)
        ->putJson("/api/financial/athletes/{$this->athlete->id}/payment", [
            'amount' => 5000,
            'status' => 'validated',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    $this->athlete->refresh();
    expect($this->athlete->payment_amount)->toBe(5000.0);
    expect($this->athlete->payment_status)->toBe('validated');
});

it('edit payment rejects invalid status', function () {
    $this->actingAs($this->financial)
        ->putJson("/api/financial/athletes/{$this->athlete->id}/payment", [
            'amount' => 5000,
            'status' => 'invalid_status',
        ])
        ->assertUnprocessable();
});

// ── tempValidate ──────────────────────────────────────────────────────────────

it('financial staff can grant temporary validation with a future deadline', function () {
    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/temp-validate", [
            'deadline' => now()->addDays(7)->format('Y-m-d'),
            'notes'    => 'Accord verbal du responsable',
        ])
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->athlete->fresh()->payment_status)->toBe('temp_validated');
});

it('temp-validate requires a future deadline', function () {
    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/temp-validate", [
            'deadline' => now()->subDay()->format('Y-m-d'),
        ])
        ->assertUnprocessable();
});

it('temp-validate requires a deadline', function () {
    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/temp-validate", [])
        ->assertUnprocessable();
});

// ── paymentDetails ────────────────────────────────────────────────────────────

it('financial staff can retrieve payment details', function () {
    $this->athlete->forceFill([
        'payment_status' => 'temp_validated',
        'payment_amount' => 5000,
        'receipt_number' => 'REC-000001',
    ])->save();

    $this->actingAs($this->financial)
        ->getJson("/api/financial/athletes/{$this->athlete->id}/payment")
        ->assertOk()
        ->assertJsonStructure([
            'data' => ['id', 'full_name', 'payment_status', 'payment_amount', 'receipt_number'],
        ])
        ->assertJsonPath('data.payment_status', 'temp_validated');
});

it('technical staff cannot view payment details', function () {
    $this->actingAs($this->technical)
        ->getJson("/api/financial/athletes/{$this->athlete->id}/payment")
        ->assertForbidden();
});

// ── bulk definitive validate ──────────────────────────────────────────────────

it('bulk definitive validate works', function () {
    $athletes = Athlete::factory(2)->create([
        'event_id'            => $this->event->id,
        'registration_status' => 'validated',
        'payment_status'      => 'temp_validated',
    ]);

    $this->actingAs($this->financial)
        ->postJson('/api/financial/bulk-validate', ['ids' => $athletes->pluck('id')->toArray()])
        ->assertOk()
        ->assertJson(['success' => true]);

    foreach ($athletes as $a) {
        expect($a->fresh()->payment_status)->toBe('validated');
    }
});
