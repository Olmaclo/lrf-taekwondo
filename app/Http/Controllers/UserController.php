<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $q = User::with('roles')->withCount('athletes');

        if ($request->search) {
            $q->where(function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->role) {
            $q->role($request->role);
        }

        $users = $q->latest()->paginate(20);

        return response()->json([
            'success' => true,
            'data'    => collect($users->items())->map(fn ($u) => [
                'id'             => $u->id,
                'name'           => $u->name,
                'email'          => $u->email,
                'phone'          => $u->phone,
                'club'           => $u->club,
                'roles'          => $u->roles->pluck('name'),
                'role'           => $u->roles->first()?->name ?? 'none',
                'is_validated'   => $u->is_validated,
                'account_status' => $u->account_status,
                'athletes_count' => $u->athletes_count,
                'avatar_url'     => $u->avatar_url,
                'created_at'     => $u->created_at->format('d/m/Y'),
            ]),
            'meta' => [
                'total'        => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page'    => $users->lastPage(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'unique:users,email'],
            'password' => ['required', Rules\Password::min(10)->letters()->numbers()],
            'role'     => ['required', 'string', 'exists:roles,name'],
            'phone'    => ['nullable', 'string'],
            'club'     => ['nullable', 'string'],
        ], [
            'name.required'     => 'Le nom est obligatoire.',
            'email.required'    => 'L\'adresse email est obligatoire.',
            'email.email'       => 'L\'adresse email n\'est pas valide.',
            'email.unique'      => 'Cette adresse email est déjà utilisée par un autre compte.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'      => 'Le mot de passe doit contenir au moins 8 caractères.',
            'role.required'     => 'Le rôle est obligatoire.',
            'role.exists'       => 'Le rôle sélectionné est invalide.',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'phone'    => $data['phone'] ?? null,
            'club'     => $data['club'] ?? null,
        ]);
        $user->assignRole($data['role']);

        return response()->json([
            'success' => true,
            'message' => "Utilisateur {$user->name} créé.",
            'data'    => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        ], 201);
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $role = $request->validate(['role' => ['required', 'string', 'exists:roles,name']])['role'];

        // Prevent admin from removing their own admin role
        if ($user->id === Auth::id() && $role !== 'admin') {
            return response()->json(['success' => false, 'message' => 'Impossible de modifier votre propre rôle.'], 403);
        }

        $previousRole = $user->roles->first()?->name ?? 'none';
        $user->syncRoles([$role]);

        Log::info('role_change', [
            'target_user_id'   => $user->id,
            'target_user_name' => $user->name,
            'previous_role'    => $previousRole,
            'new_role'         => $role,
            'changed_by'       => Auth::id(),
            'changed_by_name'  => Auth::user()->name,
            'ip'               => request()->ip(),
        ]);

        return response()->json(['success' => true, 'message' => "Rôle de {$user->name} mis à jour : {$role}."]);
    }

    public function sendPasswordReset(User $user): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $status = Password::sendResetLink(['email' => $user->email]);

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'success' => true,
                'message' => "Email de réinitialisation envoyé à {$user->name}.",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Impossible d\'envoyer l\'email : ' . __($status),
        ], 422);
    }

    public function toggleValidation(User $user): JsonResponse
    {
        abort_unless(Auth::user()->isTechnical(), 403);

        $user->update([
            'is_validated'   => !$user->is_validated,
            'account_status' => $user->is_validated ? 'pending' : 'approved',
        ]);

        $status = $user->is_validated ? 'validé' : 'suspendu';
        return response()->json(['success' => true, 'message' => "Compte de {$user->name} {$status}."]);
    }

    public function destroy(User $user): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        if ($user->id === Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer votre propre compte.'], 403);
        }

        $user->delete();

        return response()->json(['success' => true, 'message' => "Utilisateur {$user->name} supprimé."]);
    }

    public function roles(): JsonResponse
    {
        abort_unless(Auth::user()->isAdmin(), 403);

        $roles = Role::orderBy('name')->pluck('name');
        return response()->json(['success' => true, 'data' => $roles]);
    }
}
