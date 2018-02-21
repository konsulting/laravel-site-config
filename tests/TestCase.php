<?php

namespace Konsulting\Laravel\SiteConfig\Tests;

use Illuminate\Foundation\Application;
use Illuminate\Support\Carbon;
use Konsulting\Laravel\CollectionsServiceProvider;
use Konsulting\Laravel\SiteConfig\SiteConfigServiceProvider;
use Orchestra\Testbench\TestCase as TestbenchTestCase;

class TestCase extends TestbenchTestCase
{
    protected function setUp()
    {
        parent::setUp();

        Carbon::setTestNow('2018-02-20 00:00:00');

        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }

    /**
     * Get package service providers.
     *
     * @param  Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CollectionsServiceProvider::class,
            SiteConfigServiceProvider::class
        ];
    }


}
