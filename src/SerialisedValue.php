<?php

namespace Konsulting\Laravel\SiteConfig;

class SerialisedValue
{
    /**
     * The serialised value.
     *
     * @var string
     */
    protected $value;

    /**
     * The original type of the value.
     *
     * @var string
     */
    protected $originalType;

    /**
     * SerialisedValue constructor.
     *
     * @param string $value
     * @param string $originalType
     */
    public function __construct(string $value, string $originalType)
    {
        $this->value = $value;
        $this->originalType = $originalType;
    }

    /**
     * Get the serialised value.
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Get the original type.
     *
     * @return string
     */
    public function getOriginalType()
    {
        return $this->originalType;
    }
}
