<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales Incorrectas'], 401);
        }

        if (! $user->is_active) {
            return response()->json(['message' => 'Usuario inactivo'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        
        $institutions = $user->institutions()
            ->where('institutions.is_active', true)
            ->wherePivot('is_active', true)
            ->with('project')
            ->get()
            ->map(fn ($inst) => [
                'id' => $inst->id,
                'name' => $inst->name,
                'slug' => $inst->slug,
                'project' => $inst->project,
                'role' => Role::find($inst->pivot->role_id),
            ]);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'institutions' => $institutions,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    
    public function me(Request $request)
    {
        $user     = $request->user();
        $userData = $user->toArray();

        $userData['institutions'] = $user->institutions()
            ->where('institutions.is_active', true)
            ->wherePivot('is_active', true)
            ->with('project')
            ->get()
            ->map(fn($inst) => [
                'id'      => $inst->id,
                'name'    => $inst->name,
                'slug'    => $inst->slug,
                'project' => $inst->project,
                'role'    => \App\Models\Role::find($inst->pivot->role_id),
            ])
            ->toArray();

        return response()->json($userData);
    }

    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Link enviado'])
            : response()->json(['error' => 'No se pudo enviar'], 400);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])
                    ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Contraseña actualizada'])
            : response()->json(['error' => 'Token inválido o expirado'], 400);
    }
}