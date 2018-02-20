<?php

namespace Konsulting\Laravel\SiteConfig;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\QueryException;
use Illuminate\Support\Arr;
use Konsulting\Laravel\EditorStamps\EditorStamps;
use Konsulting\Laravel\Sorting\Sortable;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteConfig extends Model
{
    use SoftDeletes, EditorStamps, LogsActivity, Sortable;

    protected $table = 'site_config';

    protected $fillable = ['key', 'value', 'type', 'arguments'];

    protected static $sortableSettings = [
        'sortable'    => ['key'],
        'defaultSort' => '+key',
    ];

    /**
     * Whenever we make a change to this model, we should remove the cached values
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function () {
            \Cache::forget('site_config');
        });

        static::deleted(function () {
            \Cache::forget('site_config');
        });
    }

    /**
     * Store the specified key and value in the database, and update the config value for the current request.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    public static function put($key, $value, $type = null)
    {
        SiteConfig::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
            + (isset($type) ? ['type' => $type] : [])
        );
        config(['site_config.' . $key => static::cast($value, $type ?? null)]);

        return $value;
    }

    public static function getDotArray()
    {
        try {
            // Get the site config from the db and extract to an array
            // cache the result for 15 mins

            return \Cache::remember('site_config', 15, function () {
                $dotted = static::select('key', 'value', 'type')->get()->reduce(function ($array, $item) {
                    $array[$item->key] = static::cast($item->value, $item->type);

                    return $array;
                }, []);

                return Arr::fromDot($dotted);
            });
        } catch (QueryException $e) {
            return [];
        }
    }

    public static function cast($value, $type = null)
    {
        $type = strtolower($type);

        if (empty($type) || $type == 'string') {
            return $value;
        }

        switch ($type) {
            case 'bool':
            case 'boolean':
                $value = ($value == 'false') ? 0 : $value;

                return ! ! $value;
                break;
            case 'array':
                return unserialize($value);
                break;
            case 'json':
                return json_decode($value, true);
                break;
            case 'json_object':
                return json_decode($value);
                break;
            case 'date':
                return Carbon::parse($value);
                break;
            case 'integer':
                return (int) $value;
                break;
            case 'float':
                return (float) $value;
            case 'string':
            default:
                return $value;
        }
    }

    public static function allowedTypes()
    {
        return [
            'array',
            'boolean',
            'date',
            'float',
            'integer',
            'json',
            'json_object',
            'string',
        ];
    }
}
