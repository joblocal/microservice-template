<?php

namespace Tests\Traits;

/**
 * DatabaseMigrations Trait
 *
 * Since the testing database is only stored in memory we cannot use the
 * default DatabaseMigrations trait provided by Lumen
 */
trait DatabaseMigrations
{
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate');
    }
}
