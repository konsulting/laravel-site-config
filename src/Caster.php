<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Support\Carbon;

class Caster
{
    protected static $typeAliases = [
        'boolean' => 'bool',
        'double'  => 'float'
    ];

    /**
     * Cast a string value into the specified type.
     *
     * @param string $value
     * @param string $type
     * @return mixed
     */
    public static function cast($value, $type = 'string')
    {
        switch (static::resolveTypeAliases($type)) {
            case 'bool':
                $value = ($value == 'false') ? 0 : $value;

                return (bool) $value;
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
            case 'int':
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

    /**
     * Convert a value to string based on its type. Optionally a type override can be specified.
     *
     * @param mixed  $value
     * @param string $type
     * @return SerialisedValue
     */
    public static function serialise($value, $type = null)
    {
        $type = $type ?: static::getType($value);
        $type = static::resolveTypeAliases($type);

        switch ($type) {
            case 'bool':
                $serialised = $value ? '1' : '0';
                break;
            case 'integer':
            case 'float':
                $serialised = (string) $value;
                break;
            case 'date':
                $serialised = $value->toDateTimeString();
                break;
            case 'json':
            case 'json_object':
                $serialised = json_encode($value);
                break;
            case 'string':
                $serialised = $value;
                break;
            case 'array':
            default:
                $serialised = serialize($value);
        }

        return new SerialisedValue($serialised, $type);
    }

    /**
     * @return array
     */
    public static function allowedTypes()
    {
        return [
            'array',
            'bool',
            'date',
            'float',
            'integer',
            'json',
            'json_object',
            'string',
        ];
    }

    /**
     * Get the type of a given value.
     *
     * @param mixed $value
     * @return string
     */
    protected static function getType($value)
    {
        $type = static::resolveTypeAliases(gettype($value));

        if (in_array($type, static::allowedTypes())) {
            return $type;
        } elseif ($value instanceof \DateTime) {
            return 'date';
        }

        return $type;
    }

    /**
     * Get the aliased type if it exists in the array. If not, return the type unchanged.
     *
     * @param string $type
     * @return string
     */
    protected static function resolveTypeAliases($type)
    {
        $type = strtolower($type);

        return array_key_exists($type, static::$typeAliases)
            ? static::$typeAliases[$type]
            : $type;
    }
}
