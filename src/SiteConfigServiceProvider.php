<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Database\QueryException;
use Illuminate\Support\ServiceProvider;

class SiteConfigServiceProvider extends ServiceProvider
{
    protected $viewsDirectory = __DIR__ . '/../resources/views/';
    protected $configFile = __DIR__ . '/../config/site_config_package.php';

    public function boot()
    {
        $this->mergeConfig();

        $this->loadViewsFrom($this->viewsDirectory, 'site_config');
        $this->publishes([$this->configFile => config_path('site_config_package.php')], 'config');
        $this->publishes([$this->viewsDirectory => resource_path('views/vendor/site_config')], 'views');
    }

    /**
     * Load package-specific config.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom($this->configFile, 'site_config_package');
    }

    /**
     * Get all config items from the database and merge them into the application config. If the site config table does
     * not exist, suppress the error and move on.
     *
     * @return void
     */
    protected function mergeConfig()
    {
        try {
            SiteConfig::mergeAppConfig();
        } catch (QueryException $e) {
            $this->suppressIfTableDoesntExist($e);
        }
    }

    /**
     * Suppress the query exception only if it relates to the database table not existing.
     *
     * @param QueryException $e
     * @return void
     */
    protected function suppressIfTableDoesntExist(QueryException $e)
    {
        if ($e->getPrevious()->getCode() !== 'HY000') {
            throw $e;
        }
    }
}
