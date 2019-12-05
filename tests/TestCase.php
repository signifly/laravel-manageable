<?php

namespace Signifly\Manageable\Test;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase as Orchestra;
use Signifly\Manageable\ManageableServiceProvider;
use Signifly\Manageable\Test\Models\User;

abstract class TestCase extends Orchestra
{
    use WithFaker;

    public function setUp(): void
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

    protected function setUpDatabase(): void
    {
        $this->createTables();
        $this->seedTables();
    }

    protected function createTables(): void
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
            $table->manageable(false);
        });

        $this->app['config']->set('auth.providers.users.model', User::class);
    }

    protected function seedTables(): void
    {
        $this->createUser(['name' => 'John Doe']);
    }

    protected function createUser(array $data = []): User
    {
        return User::create(array_merge([
            'name' => $this->faker->name,
            'token' => $this->faker->uuid,
        ], $data));
    }
}
