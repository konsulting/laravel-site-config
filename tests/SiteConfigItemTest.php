<?php

namespace Konsulting\Laravel\SiteConfig\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Konsulting\Laravel\SiteConfig\SiteConfigItem;

class SiteConfigItemTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_sets_a_value_in_the_database()
    {
        $value = SiteConfigItem::setItem('foo', 'bar');

        $this->assertDatabaseHas('site_config', ['key' => 'foo', 'value' => 'bar']);
        $this->assertSame('bar', $value);
    }

    /** @test */
    public function it_gets_an_item_from_the_database()
    {
        SiteConfigItem::setItem('foo', 'bar');

        $this->assertSame('bar', SiteConfigItem::getItem('foo'));
    }

    /** @test */
    public function it_gets_the_config_array()
    {
        SiteConfigItem::setItem('foo.bar', 'one');
        SiteConfigItem::setItem('foo.baz', 'two');
        SiteConfigItem::setItem('something', 'three');

        $expected = [
            'foo'       => ['bar' => 'one', 'baz' => 'two'],
            'something' => 'three',
        ];

        $this->assertSame($expected, SiteConfigItem::getConfigArray());
    }

    /** @test */
    public function it_gets_its_casted_value()
    {
        $item = SiteConfigItem::create([
            'key'   => 'foo',
            'value' => '123',
            'type'  => 'int'
        ]);

        $this->assertSame(123, $item->getCastedValue());
    }

    /** @test */
    public function it_casts_items_when_reading_from_database()
    {
        SiteConfigItem::setItem('foo', 123);

        $this->assertSame(123, SiteConfigItem::getItem('foo'));
    }
}
