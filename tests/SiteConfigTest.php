<?php

namespace Konsulting\Laravel\SiteConfig\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Konsulting\Laravel\SiteConfig\SiteConfig;
use Konsulting\Laravel\SiteConfig\SiteConfigItem;

class SiteConfigTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_get_an_item_from_app_config()
    {
        $this->app['config']['site_config'] = ['foo' => 'bar'];

        $this->assertEquals('bar', SiteConfig::get('foo'));
    }

    /** @test */
    public function it_sets_a_config_item_in_the_database_and_config()
    {
        SiteConfig::set('bar', 'baz', 'json');

        $this->assertEquals('baz', config('site_config.bar'));
        $this->assertSame('baz', SiteConfigItem::getItem('bar'));
    }

    /** @test */
    public function it_merges_database_config_with_app_config()
    {
        SiteConfigItem::setItem('foo.bar', 'three');
        SiteConfigItem::setItem('something', 'test');

        $this->app['config']['site_config'] = [
            'foo' => [
                'bar' => 'one',
                'baz' => 'two'
            ]
        ];

        $mergedConfig = SiteConfig::mergeAppConfig();

        $expected = [
            'foo'       => [
                'bar' => 'three',
                'baz' => 'two'
            ],
            'something' => 'test',
        ];

        $this->assertSame($expected, config('site_config'));
        $this->assertSame($expected, $mergedConfig);
    }
}
