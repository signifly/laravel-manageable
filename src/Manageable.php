<?php

namespace Signifly\Manageable;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

trait Manageable
{
    public static function bootManageable(): void
    {
        static::creating(function (Model $model) {
            $model->setManageable('created_by', 'creator');
        });

        static::updating(function (Model $model) {
            $model->setManageable('updated_by', 'editor');
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo($this->getManageableUsersModel(), 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo($this->getManageableUsersModel(), 'updated_by');
    }

    public function hasCreator(): bool
    {
        return ! is_null($this->created_by);
    }

    public function hasEditor(): bool
    {
        return ! is_null($this->updated_by);
    }

    protected function setManageable(string $type, string $relation): void
    {
        $guard = $this->getManageableGuard();

        if ($guard->check()) {
            $this->{$type} = $guard->id();
            $this->setRelation($relation, $guard->user());
        }
    }

    protected function getManageableUsersModel()
    {
        return method_exists($this, 'manageableUsersModel')
            ? $this->manageableUsersModel()
            : ($this->manageableUsersModel ?? config('auth.providers.users.model'));
    }

    protected function getManageableGuard()
    {
        $manageableGuardName = method_exists($this, 'manageableGuardName')
            ? $this->manageableGuardName()
            : ($this->manageableGuardName ?? config('auth.defaults.guard'));

        return Auth::guard($manageableGuardName);
    }
}
