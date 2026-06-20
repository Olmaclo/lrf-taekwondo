<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        // Rate limiting — 3 tentatives max par IP+email par heure
        $key = 'password-reset:' . sha1($request->ip() . '|' . $request->email);
        if (RateLimiter::tooManyAttempts($key, 3)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                'email' => "Trop de tentatives. Réessayez dans {$seconds} seconde(s).",
            ]);
        }
        RateLimiter::hit($key, 3600);

        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'L\'adresse e-mail est requise.',
            'email.email'    => 'Adresse e-mail invalide.',
        ]);

        $status = Password::sendResetLink($request->only('email'));

        // Même message que l'email existe ou non — évite l'énumération de comptes
        return back()->with('status', 'Si cette adresse est associée à un compte, un lien de réinitialisation vient d\'être envoyé.');
    }
}
