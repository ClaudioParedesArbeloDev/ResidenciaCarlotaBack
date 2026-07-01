<?php
namespace App\Scopes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
class InstitutionScope implements Scope {
    public function apply(Builder $builder, Model $model): void {
        if (app()->bound('current_institution')) {
            $builder->where($model->getTable() . '.institution_id', app('current_institution')->id);
        }
    }
}
