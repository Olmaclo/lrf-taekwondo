<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\NewCoachRegisteredMail;
use App\Models\User;
use App\Rules\NotDisposableEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        // Rate limiting — 5 tentatives max par IP par minute
        $key = 'register:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives d'inscription. Réessayez dans {$seconds} seconde(s).",
            ]);
        }
        RateLimiter::hit($key, 60);

        $data = $request->validate([
            'first_name'     => ['required', 'string', 'max:100'],
            'last_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email:rfc,dns', 'unique:users,email', new NotDisposableEmail()],
            'password'       => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
            'club'           => ['required', 'string', 'max:150'],
            'birth_date'     => ['required', 'date', 'before:today'],
            'birth_place'    => ['required', 'string', 'max:150'],
            'license_number' => ['required', 'string', 'max:50'],
            'federal_code'   => ['required', 'digits_between:1,20'],
        ], [
            'email.email'  => 'Cette adresse email est invalide ou le domaine n\'existe pas.',
            'email.unique' => 'Cette adresse email est déjà associée à un compte.',
        ]);

        $user = User::create([
            'name'           => trim($data['first_name'] . ' ' . $data['last_name']),
            'email'          => $data['email'],
            'password'       => Hash::make($data['password']),
            'club'           => $data['club'],
            'birth_date'     => $data['birth_date'],
            'birth_place'    => $data['birth_place'],
            'license_number' => $data['license_number'],
            'federal_code'   => $data['federal_code'],
            'is_validated'   => false,
            'account_status' => 'pending',
        ]);

        $user->assignRole('coach');

        // Notify all admins
        $admins = User::role('admin')->get();
        foreach ($admins as $admin) {
            try {
                Mail::to($admin->email)->send(new NewCoachRegisteredMail($user));
            } catch (\Throwable) {}
        }

        return redirect()->route('login')
            ->with('success', 'Compte créé avec succès. Votre compte est en attente de validation par un administrateur.');
    }
}
