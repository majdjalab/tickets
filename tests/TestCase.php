<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Schema;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

            $this->prepareDatabase();


        // If models changed during the single test, there must be reset to
        // inital values.
        //static::$testEnvironment?->refresh();
    }

    /**
     * Creates an empty database and fills it with default data for the test environment,
     * if the table fa_netzwerk not exists.
     *
     * @return void
     */
    protected function prepareDatabase()
    {
        try {
            if (!Schema::hasTable('categories')) {
                $this->artisan('migrate:fresh --env=testing')->assertExitCode(0);
//                $this->artisan(
//                    'db:seed --class TestingEnvironmentSeeder --env=testing'
//                )->assertExitCode(0);
            }
//            static::$setUpHasRunOnce = true;
        } catch (Exception $e) {
            // An exception was thrown, all tests abort, the database could not
            // be initialized.
            die($e);
        }
    }
}
