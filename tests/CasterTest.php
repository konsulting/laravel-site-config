<?php

namespace Konsulting\Laravel\SiteConfig\Tests;

use Carbon\Carbon;
use Konsulting\Laravel\SiteConfig\Caster;

class CasterTest extends TestCase
{
    /** @test */
    public function it_casts_to_the_given_type()
    {
        $this->assertTrue(Caster::cast(1, 'bool'));
        $this->assertTrue(Caster::cast(1, 'boolean'));
        $this->assertSame(['foo' => 'bar'], Caster::cast(serialize(['foo' => 'bar']), 'array'));
        $this->assertSame(['foo' => 'bar'], Caster::cast('{"foo": "bar"}', 'json'));
        $this->assertEquals((object) ['foo' => 'bar'], Caster::cast('{"foo": "bar"}', 'json_object'));
        $this->assertEquals(Carbon::now(), Caster::cast(Carbon::now()->toDateTimeString(), 'date'));
        $this->assertSame(123, Caster::cast('123', 'int'));
        $this->assertSame(123, Caster::cast('123', 'integer'));
        $this->assertSame(123.4, Caster::cast(123.4, 'float'));
        $this->assertSame('foo', Caster::cast('foo', 'string'));
    }

    /** @test */
    public function it_casts_to_string_based_on_value_type()
    {
        $this->assertSame('bool', Caster::serialise(true)->getOriginalType());
        $this->assertSame('1', Caster::serialise(true)->getValue());

        $this->assertSame('array', Caster::serialise(['foo' => 'bar'])->getOriginalType());
        $this->assertSame(serialize(['foo' => 'bar']), Caster::serialise(['foo' => 'bar'])->getValue());

        $this->assertSame('integer', Caster::serialise(123)->getOriginalType());
        $this->assertSame('123', Caster::serialise(123)->getValue());

        $this->assertSame('float', Caster::serialise(123.4)->getOriginalType());
        $this->assertSame('123.4', Caster::serialise(123.4)->getValue());

        $this->assertSame('date', Caster::serialise(Carbon::now())->getOriginalType());
        $this->assertSame(Carbon::now()->toDateTimeString(), Caster::serialise(Carbon::now())->getValue());

        $this->assertSame('json', Caster::serialise(['foo' => 'bar'], 'json')->getOriginalType());
        $this->assertSame('{"foo":"bar"}', Caster::serialise(['foo' => 'bar'], 'json')->getValue());

        $this->assertSame('json_object', Caster::serialise(['foo' => 'bar'], 'json_object')->getOriginalType());
        $this->assertSame('{"foo":"bar"}', Caster::serialise(['foo' => 'bar'], 'json_object')->getValue());

        $this->assertSame('string', Caster::serialise('foo')->getOriginalType());
        $this->assertSame('foo', Caster::serialise('foo')->getValue());
    }
}
