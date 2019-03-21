<?php

namespace Signifly\Manageable;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

trait Manageable
{
    protected static function bootManageable()
    {
        static::creating(function (Model $model) {
            $model->setManageable('created_by', 'creator');
        });

        static::updating(function (Model $model) {
            $model->setManageable('updated_by', 'editor');
        });
    }

    public function creator()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'updated_by');
    }

    public function hasCreator() : bool
    {
        return !is_null($this->created_by);
    }

    public function hasEditor() : bool
    {
        return !is_null($this->updated_by);
    }

    protected function setManageable(string $type, string $relation)
    {
        if (!isset($this->{$type}) && Auth::check()) {
            $this->{$type} = Auth::id();
            $this->setRelation($relation, Auth::user());
        }
    }
}
