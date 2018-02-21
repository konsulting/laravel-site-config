<?php

namespace Konsulting\Laravel\SiteConfig\Tests;

use Konsulting\Laravel\SiteConfig\SerialisedValue;

class SerialisedValueTest extends TestCase
{
    /** @test */
    public function it_has_a_value_and_an_original_type()
    {
        $value = new SerialisedValue('123', 'int');

        $this->assertSame('123', $value->getValue());
        $this->assertSame('int', $value->getOriginalType());
    }
}
