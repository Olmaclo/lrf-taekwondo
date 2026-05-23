<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::firstOrCreate(['name' => 'admin',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'technical', 'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'coach',     'guard_name' => 'web']);
    Role::firstOrCreate(['name' => 'financial', 'guard_name' => 'web']);
});

it('shows the login page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Connexion');
});

it('redirects authenticated users away from login', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)->get('/')->assertRedirect('/dashboard');
});

it('logs in with valid credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('password')]);
    $user->assignRole('technical');

    $this->post('/login', ['email' => $user->email, 'password' => 'password'])
        ->assertRedirect();

    $this->assertAuthenticatedAs($user);
});

it('rejects invalid credentials', function () {
    $user = User::factory()->create(['password' => bcrypt('correct')]);

    $this->post('/login', ['email' => $user->email, 'password' => 'wrong'])
        ->assertSessionHasErrors();

    $this->assertGuest();
});

it('logs out an authenticated user', function () {
    $user = User::factory()->create();
    $user->assignRole('admin');

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});

it('redirects unauthenticated users to login', function () {
    $this->get('/dashboard')->assertRedirect('/');
});
