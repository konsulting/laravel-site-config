<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Support\Facades\Route;

class SiteConfigRoutes
{
    /**
     * Load the route files.
     */
    public static function load()
    {
        Route::get('site_config', '\\Konsulting\\Laravel\\SiteConfig\\SiteConfigController@index')->name('admin.site_config.index');
        Route::post('site_config', '\\Konsulting\\Laravel\\SiteConfig\\SiteConfigController@store')->name('admin.site_config.store');
    }
}
