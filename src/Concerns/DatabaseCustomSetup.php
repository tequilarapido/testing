<?php

namespace Tequilarapido\Testing\Concerns;

use Illuminate\Contracts\Console\Kernel;

/**
 * Based on Adam Wathan alternative db testing workflow.
 *
 * @see https://adamwathan.me/2016/11/14/a-better-database-testing-workflow-in-laravel/
 */
trait DatabaseCustomSetup
{
    protected static $migrated = false;

    public function setupDatabase()
    {
        if ($this->isInMemory()) {
            $this->setupInMemoryDatabase();
        } else {
            $this->setupTestDatabase();
        }
    }

    protected function isInMemory()
    {
        return config('database.connections')[config('database.default')]['database'] == ':memory:';
    }

    protected function setupInMemoryDatabase()
    {
        $this->artisan('migrate');
        $this->app[Kernel::class]->setArtisan(null);
    }

    protected function setupTestDatabase()
    {
        if (!static::$migrated) {
            $this->artisan('migrate:refresh');
            $this->seedUsingSeeders();
            $this->app[Kernel::class]->setArtisan(null);
            static::$migrated = true;
        }
        $this->beginDatabaseTransaction();
    }

    public function beginDatabaseTransaction()
    {
        $database = $this->app->make('db');

        foreach ($this->connectionsToTransact() as $name) {
            $database->connection($name)->beginTransaction();
        }

        $this->beforeApplicationDestroyed(function () use ($database) {
            foreach ($this->connectionsToTransact() as $name) {
                $database->connection($name)->rollBack();
            }
            $this->truncateModels();
        });
    }

    protected function connectionsToTransact()
    {
        return property_exists($this, 'connectionsToTransact')
            ? $this->connectionsToTransact : [null];
    }

    protected function seedUsingSeeders()
    {
        if (property_exists($this, 'useSeeders')) {
            foreach ((array) $this->useSeeders as $class) {
                $this->artisan('db:seed', ['--class' => $class]);
            }
        }
    }

    protected function truncateModels()
    {
        if (property_exists($this, 'truncateModels')) {
            foreach ((array) $this->truncateModels as $class) {
                forward_static_call_array([$class, 'truncate'], []);
            }
        }
    }
}
