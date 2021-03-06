<?php

namespace Konsulting\Laravel\SiteConfig;

class SiteConfig
{
    /**
     * Get an item from site config. All database items should already be merged into the application config, so no
     * need to check the database.
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return static::appConfig()->get(static::namespace($key));
    }

    /**
     * Set an item in config. Write it to the database with the relevant type.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     */
    public static function set($key, $value, $type = null)
    {
        SiteConfigItem::setItem($key, $value, $type);
        static::appConfig()->set(static::namespace($key), $value);
    }

    /**
     * Get the application config repository.
     *
     * @return \Illuminate\Config\Repository
     */
    protected static function appConfig()
    {
        return app('config');
    }

    /**
     * Get the config namespace. If a key is supplied, get the qualified key path.
     *
     * @param string $key
     * @return string
     */
    protected static function namespace($key = null)
    {
        return is_null($key)
            ? config('site_config_package.config_namespace')
            : config('site_config_package.config_namespace') . '.' . $key;
    }

    /**
     * Get all config items from the database and merge with the app config.
     *
     * @return array
     */
    public static function mergeAppConfig()
    {
        $mergedConfig = array_replace_recursive(static::appConfig()->get('site_config', []),
            SiteConfigItem::getConfigArray());
        static::appConfig()->set('site_config', $mergedConfig);

        return $mergedConfig;
    }
}
