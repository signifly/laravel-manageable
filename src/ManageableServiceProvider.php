<?php

namespace Signifly\Manageable;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Schema\Blueprint;

class ManageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('manageable', function ($foreignTable = 'users', $foreignKey = 'id') {
            $this->unsignedInteger('created_by')->nullable()->index();
            $this->unsignedInteger('updated_by')->nullable()->index();

            $this->foreign('created_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('cascade');

            $this->foreign('updated_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('cascade');
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
    }
}
