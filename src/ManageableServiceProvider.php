<?php

namespace Signifly\Manageable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class ManageableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $bigIntegers = config('manageable.big_integers', true);
        $foreignTable = config('manageable.foreign_table', 'users');
        $foreignId = config('manageable.foreign_id', 'id');

        Blueprint::macro('manageable', function ($bigIntegers, $foreignTable, $foreignKey) {
            $bigIntegers
                ? $this->unsignedBigInteger('created_by')->nullable()->index()
                : $this->unsignedInteger('created_by')->nullable()->index();
            $bigIntegers
                ? $this->unsignedBigInteger('updated_by')->nullable()->index()
                : $this->unsignedInteger('updated_by')->nullable()->index();

            $this->foreign('created_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('set null');

            $this->foreign('updated_by')
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('set null');
        });

        $this->publishes([
            __DIR__.'/../config/manageable.php' => config_path('manageable.php')
        ], 'config');
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
