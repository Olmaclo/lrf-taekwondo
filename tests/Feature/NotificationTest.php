<?php

use App\Mail\AthleteRejectedMail;
use App\Mail\AthleteValidatedMail;
use App\Mail\CoachValidatedMail;
use App\Mail\NewCoachRegisteredMail;
use App\Models\Athlete;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    foreach (['admin', 'technical', 'financial', 'coach'] as $role) {
        Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
    }

    $this->technical = User::factory()->create();
    $this->technical->assignRole('technical');

    $this->coach = User::factory()->create();
    $this->coach->assignRole('coach');

    $this->event = Event::factory()->create(['status' => 'open']);

    Mail::fake();
});

it('sends AthleteValidatedMail to coach when athlete is validated', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $this->coach->id,
        'registration_status' => 'pending',
        'weight_category'     => '-68kg',
    ]);

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$athlete->id}/validate")
        ->assertOk();

    Mail::assertSent(AthleteValidatedMail::class, fn ($m) => $m->hasTo($this->coach->email));
});

it('sends AthleteRejectedMail to coach when athlete is rejected', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => $this->coach->id,
        'registration_status' => 'pending',
    ]);

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$athlete->id}/reject", ['reason' => 'Dossier incomplet'])
        ->assertOk();

    Mail::assertSent(AthleteRejectedMail::class, fn ($m) => $m->hasTo($this->coach->email));
});

it('sends CoachValidatedMail when coach account is approved', function () {
    $this->actingAs($this->technical)
        ->postJson("/api/coaches/{$this->coach->id}/validate")
        ->assertOk();

    Mail::assertSent(CoachValidatedMail::class, fn ($m) => $m->hasTo($this->coach->email));
});

it('sends NewCoachRegisteredMail to admins on coach registration', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->post('/register', [
        'first_name'     => 'Nouveau',
        'last_name'      => 'Coach',
        'email'          => 'nouveau@coach.com',
        'password'       => 'Password123',
        'password_confirmation' => 'Password123',
        'club'           => 'Club Test',
        'birth_date'     => '1990-01-01',
        'birth_place'    => 'Dakar',
        'license_number' => 'LIC-999',
        'federal_code'   => '12345',
    ])->assertRedirect();

    Mail::assertSent(NewCoachRegisteredMail::class, fn ($m) => $m->hasTo($admin->email));
});

it('no mail sent when athlete has no coach email', function () {
    $athlete = Athlete::factory()->create([
        'event_id'            => $this->event->id,
        'coach_id'            => null,
        'registration_status' => 'pending',
        'weight_category'     => '-68kg',
    ]);

    $this->actingAs($this->technical)
        ->postJson("/api/athletes/{$athlete->id}/validate")
        ->assertOk();

    Mail::assertNothingSent();
});
