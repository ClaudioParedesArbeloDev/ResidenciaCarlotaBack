<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'project_id', 'name', 'slug', 'address',
        'phone', 'email', 'logo', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

  
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'institution_user')
            ->withPivot(['role_id', 'is_active'])
            ->withTimestamps();
    }

    public function patients(): HasMany
    {
        return $this->hasMany(Patient::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
