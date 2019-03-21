<?php

namespace Signifly\Manageable;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\ServiceProvider;

class ManageableServiceProvider extends ServiceProvider
{
    protected $createdBy = 'created_by';
    protected $createdBy = 'updated_by';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blueprint::macro('manageable', function ($bigIntegers = true, $foreignTable = 'users', $foreignKey = 'id') {
            $createdBy = $bigIntegers
                ? $this->unsignedBigInteger($this->createdBy)
                : $this->unsignedInteger($this->createdBy);
            $updatedBy = $bigIntegers
                ? $this->unsignedBigInteger($this->updatedBy)
                : $this->unsignedInteger($this->updatedBy);

            $createdBy->nullable()->index();
            $createdBy->nullable()->index();

            $this->foreign($this->createdBy)
                ->references($foreignKey)
                ->on($foreignTable)
                ->onDelete('cascade');

            $this->foreign($this->updatedBy)
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
