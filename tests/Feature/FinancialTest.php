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
    $this->athlete->update([
        'payment_status' => 'temp_validated',
        'payment_amount' => 5000,
    ]);

    $this->actingAs($this->financial)
        ->postJson("/api/financial/athletes/{$this->athlete->id}/validate")
        ->assertOk()
        ->assertJson(['success' => true]);

    expect($this->athlete->fresh()->payment_status)->toBe('validated');
});

it('generates a receipt for paid athlete', function () {
    $this->athlete->update([
        'payment_status' => 'validated',
        'payment_amount' => 5000,
        'receipt_number' => 'REC-000001',
    ]);

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

it('bulk temp validate works', function () {
    $athletes = Athlete::factory(3)->create([
        'event_id'       => $this->event->id,
        'payment_status' => 'unpaid',
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
