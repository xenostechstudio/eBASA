<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::creating(function ($model): void {
            if (! Auth::check()) {
                return;
            }

            $userId = Auth::id();

            if (empty($model->created_by)) {
                $model->created_by = $userId;
            }

            if (empty($model->updated_by)) {
                $model->updated_by = $userId;
            }
        });

        static::updating(function ($model): void {
            if (! Auth::check()) {
                return;
            }

            $model->updated_by = Auth::id();
        });

        static::deleting(function ($model): void {
            // Only apply deleted_by for soft deletes
            if (method_exists($model, 'isForceDeleting') && $model->isForceDeleting()) {
                return;
            }

            if (! Auth::check()) {
                return;
            }

            $model->deleted_by = Auth::id();

            // Avoid firing events again
            $model->saveQuietly();
        });
    }
}
