<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Medication extends Model
{
    protected $fillable = [
        'code',
        'nombre_comercial',
        'presentacion',
        'accion_farmacologica',
        'principio_activo',
        'laboratorio',
        'porcentaje',
        'seccion',
    ];

    
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function (Builder $q) use ($term) {
            $like = '%' . $term . '%';
            $q->where('nombre_comercial', 'like', $like)
              ->orWhere('principio_activo', 'like', $like)
              ->orWhere('presentacion',     'like', $like)
              ->orWhere('laboratorio',      'like', $like)
              ->orWhere('code',             'like', $like);
        });
    }

    
    public function scopePorcentaje(Builder $query, int $porcentaje): Builder
    {
        return $query->where('porcentaje', $porcentaje);
    }

    
    public function scopeSeccion(Builder $query, string $seccion): Builder
    {
        return $query->where('seccion', $seccion);
    }
}