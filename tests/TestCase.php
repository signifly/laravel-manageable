<?php

namespace Signifly\Manageable\Test;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Signifly\Manageable\ManageableServiceProvider;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->setUpDatabase();
    }

    public function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
        $app['config']->set('app.key', 'base64:9e0yNQB60wgU/cqbP09uphPo3aglW3iQJy+u4JQgnQE=');
    }

    protected function getPackageProviders($app)
    {
        return [
            ManageableServiceProvider::class,
        ];
    }

    protected function setUpDatabase()
    {
        $this->createTables();
        $this->seedTables();
    }

    protected function createTables()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('token');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->timestamps();
            $table->manageable();
        });
    }

    protected function seedTables()
    {
        $now = Carbon::now()->toDateTimeString();

        DB::table('users')->insert([
            'name'       => 'John Doe',
            'token'      => md5('token'),
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
