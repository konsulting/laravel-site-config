<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Konsulting\Laravel\EditorStamps\EditorStamps;
use Konsulting\Laravel\Sorting\Sortable;
use Spatie\Activitylog\Traits\LogsActivity;

class SiteConfigItem extends Model
{
//    use SoftDeletes, EditorStamps, LogsActivity, Sortable;

    protected $table = 'site_config';

    /**
     * Use the config item key as the table's primary key.
     *
     * @var string
     */
    protected $primaryKey = 'key';

    /**
     * The database table uses a string primary key.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The mass-assignable attributes.
     *
     * @var array
     */
    protected $fillable = ['key', 'value', 'type', 'arguments'];

    protected static $sortableSettings = [
        'sortable'    => ['key'],
        'defaultSort' => '+key',
    ];

    /**
     * Whenever we make a change to this model, we should remove the cached values
     */
//    public static function boot()
//    {
//        parent::boot();
//
//        static::saved(function () {
//            \Cache::forget('site_config');
//        });
//
//        static::deleted(function () {
//            \Cache::forget('site_config');
//        });
//    }

    /**
     * Store the specified key and value in the database, and update the config value for the current request.
     *
     * @param string $key
     * @param mixed  $value
     * @param string $type
     * @return mixed
     */
    public static function setItem($key, $value, $type = null)
    {
        $serialised = Caster::serialise($value, $type);
        static::updateOrCreate(compact('key'), [
            'value' => $serialised->getValue(),
            'type'  => $serialised->getOriginalType(),
        ]);

        return $value;
    }

    /**
     * Get an item by key.
     *
     * @param string $key
     * @return mixed
     */
    public static function getItem($key)
    {
        $item = static::whereKey($key)->first();

        return $item ? $item->getCastedValue() : null;
    }

    /**
     * Construct and return an associative array based on the key/value pairs in the database. The keys are stored in
     * the database in dot notation.
     *
     * @return array
     */
    public static function getConfigArray()
    {
//        return \Cache::remember('site_config', 15, function () {
        $dotted = static::select('key', 'value', 'type')->get()
            ->mapWithKeys(function (SiteConfigItem $item) {
                return [$item->key => $item->getCastedValue()];
            })
            ->toArray();

        return Arr::fromDot($dotted);
//        });
    }

    /**
     * Get the casted value based on the 'type' property.
     *
     * @return mixed
     */
    public function getCastedValue()
    {
        return Caster::cast($this->value, $this->type);
    }
}
