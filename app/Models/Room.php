<?php

namespace App\Models;

use App\Scopes\InstitutionScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $fillable = [
        'institution_id', 'number', 'name', 'floor', 'capacity', 'type', 'status',
    ];

    protected static function booted(): void
    {
        static::addGlobalScope(new InstitutionScope());

        static::creating(function (Room $room) {
            if (! $room->institution_id && app()->bound('current_institution')) {
                $room->institution_id = app('current_institution')->id;
            }
        });
    }

    public function institution(): BelongsTo { return $this->belongsTo(Institution::class); }
    public function beds(): HasMany          { return $this->hasMany(Bed::class); }
}
