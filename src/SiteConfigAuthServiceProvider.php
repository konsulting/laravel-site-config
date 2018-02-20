<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class SiteConfigAuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        SiteConfig::class => SiteConfigPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
