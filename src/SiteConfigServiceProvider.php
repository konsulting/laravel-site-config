<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Support\ServiceProvider;

class SiteConfigServiceProvider extends ServiceProvider
{
    protected $viewsDirectory = __DIR__ . '/../resources/views/';
    protected $configFile = __DIR__ . '/../config/site_config_package.php';

    public function boot()
    {
        $mergedConfig = array_replace_recursive(config('site_config', []), SiteConfig::getDotArray());

        $this->app['config']->set('site_config', $mergedConfig);

        $this->loadViewsFrom($this->viewsDirectory, 'site_config');
        $this->publishes([$this->configFile => config_path('site_config_package.php')], 'config');
        $this->publishes([$this->viewsDirectory => resource_path('views/vendor/site_config')], 'views');
    }

    public function register()
    {
        $this->mergeConfigFrom($this->configFile, 'site_config_package');
    }
}
