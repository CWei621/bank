<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function initDatabase()
    {
        Artisan::call('migrate');
    }

    protected function resetDatabase()
    {
        Artisan::call('migrate:reset');
    }

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate:reset');
        Artisan::call('migrate');
        Artisan::call('db:seed');
    }
}
