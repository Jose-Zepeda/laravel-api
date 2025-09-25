<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        // Aplicar scope global para filtrar por tenant
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (app()->bound('tenant')) {
                $builder->where('tenant_id', app('tenant')->id);
            }
        });

        // Al crear, automáticamente asignar el tenant_id
        static::creating(function ($model) {
            if (app()->bound('tenant') && !$model->tenant_id) {
                $model->tenant_id = app('tenant')->id;
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(\App\Models\Tenant::class, 'tenant_id');
    }
}