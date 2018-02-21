<?php

namespace Konsulting\Laravel\SiteConfig;

class SiteConfig
{
    /**
     * @var string
     */
    protected static $configNamespace = 'site_config';

    /**
     * Get an item from site config.
     *
     * @param string $key
     * @return mixed
     */
    public static function get($key)
    {
        return static::appConfig()->get(static::namespace($key));
    }

    /**
     * @param string $key
     * @param mixed  $value
     * @param null   $type
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
     * @param string|null $key
     * @return string
     */
    protected static function namespace($key = null)
    {
        return is_null($key)
            ? static::$configNamespace
            : static::$configNamespace . '.' . $key;
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
