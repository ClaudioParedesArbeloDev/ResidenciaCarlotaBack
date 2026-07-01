<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
   
    public function redirect()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

   
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->away('http://localhost:8888?error=auth_failed');
        }

        $user = $this->findOrCreateUser(
            googleId: $googleUser->getId(),
            email:    $googleUser->getEmail(),
            name:     $googleUser->user['given_name']  ?? $googleUser->getName(),
            lastName: $googleUser->user['family_name'] ?? null,
            avatar:   $googleUser->getAvatar(),
        );

        if (! $user->is_active) {
            return redirect()->away('http://localhost:8888?error=cuenta_desactivada');
        }

        $token = $user->createToken('google_desktop')->plainTextToken;

        return redirect()->away('http://localhost:8888?token=' . $token);
    }

   
    public function tokenLogin(): JsonResponse
    {
        $idToken = request('id_token');

        if (! $idToken) {
            return response()->json(['message' => 'Se requiere id_token'], 422);
        }

        try {
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($idToken);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token de Google inválido'], 401);
        }

        $user = $this->findOrCreateUser(
            googleId: $googleUser->getId(),
            email:    $googleUser->getEmail(),
            name:     $googleUser->user['given_name']  ?? $googleUser->getName(),
            lastName: $googleUser->user['family_name'] ?? null,
            avatar:   $googleUser->getAvatar(),
        );

        if (! $user->is_active) {
            return response()->json(['message' => 'Usuario inactivo'], 403);
        }

        $token = $user->createToken('google_mobile')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer',
            'user'         => $user,
            'institutions' => $this->buildInstitutions($user),
        ]);
    }

   

    private function findOrCreateUser(
        string  $googleId,
        string  $email,
        ?string $name     = null,
        ?string $lastName = null,
        ?string $avatar   = null,
    ): User {
        $user = User::where('google_id', $googleId)->first()
            ?? User::where('email', $email)->first();

        if ($user) {
            if (! $user->google_id) {
                $user->update(['google_id' => $googleId, 'avatar' => $avatar]);
            }
        } else {
            $user = User::create([
                'name'      => $name ?? $email,
                'last_name' => $lastName,
                'email'     => $email,
                'google_id' => $googleId,
                'avatar'    => $avatar,
                'password'  => bcrypt(Str::random(24)),
                'is_active' => true,
            ]);
        }

        return $user;
    }

    private function buildInstitutions(User $user): array
    {
        return $user->institutions()
            ->where('institutions.is_active', true)
            ->wherePivot('is_active', true)
            ->with('project')
            ->get()
            ->map(fn($inst) => [
                'id'      => $inst->id,
                'name'    => $inst->name,
                'slug'    => $inst->slug,
                'project' => $inst->project,
                'role'    => Role::find($inst->pivot->role_id),
            ])
            ->toArray();
    }
}