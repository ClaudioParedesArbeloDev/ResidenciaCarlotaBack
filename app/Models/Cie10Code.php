<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cie10Code extends Model
{
    protected $fillable = ['code', 'description'];

    public function scopeSearch(Builder $query, string $term): Builder
    {
        $like = '%' . $term . '%';

        return $query->where(function (Builder $q) use ($like) {
            $q->where('code', 'like', $like)
              ->orWhere('description', 'like', $like);
        });
    }
}