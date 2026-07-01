<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable([
    'name', 'last_name', 'dni', 'birth_date', 'email',
    'phone', 'address', 'employee_code', 'license_number',
    'hire_date', 'avatar', 'is_active', 'is_super_admin', 'password',
    'google_id',
])]

#[Hidden(['password', 'remember_token'])]

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'birth_date'        => 'date',
            'hire_date'         => 'date',
            'is_active'         => 'boolean',
            'is_super_admin'    => 'boolean',
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }



    public function institutions(): BelongsToMany
    {
        return $this->belongsToMany(Institution::class, 'institution_user')
            ->withPivot(['role_id', 'is_active'])
            ->withTimestamps();
    }

    public function roleInInstitution(int $institutionId): ?Role
    {
        $pivot = $this->institutions()
            ->wherePivot('institution_id', $institutionId)
            ->wherePivot('is_active', true)
            ->first();

        return $pivot ? Role::find($pivot->pivot->role_id) : null;
    }

    public function belongsToInstitution(int $institutionId): bool
    {
        return $this->institutions()
            ->wherePivot('institution_id', $institutionId)
            ->wherePivot('is_active', true)
            ->exists();
    }

    public function hasRoleInInstitution(string $roleName, int $institutionId): bool
    {
        return $this->roleInInstitution($institutionId)?->name === $roleName;
    }

    public function hasAnyRoleInInstitution(array $roleNames, int $institutionId): bool
    {
        $role = $this->roleInInstitution($institutionId);
        return $role && in_array($role->name, $roleNames);
    }

   
    public function specialties(): BelongsToMany
    {
        return $this->belongsToMany(Specialty::class)->withTimestamps();
    }

    
    public function diagnoses(): HasMany     { return $this->hasMany(PatientDiagnosis::class); }
    public function studies(): HasMany       { return $this->hasMany(MedicalStudy::class); }
    public function evolutions(): HasMany    { return $this->hasMany(PatientEvolution::class); }
    public function prescriptions(): HasMany { return $this->hasMany(MedicalPrescription::class); }
    public function vitalSigns(): HasMany    { return $this->hasMany(VitalSign::class); }
}