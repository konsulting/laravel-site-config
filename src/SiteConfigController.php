<?php

namespace Konsulting\Laravel\SiteConfig;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;

class SiteConfigController extends Controller
{
    public function __construct()
    {
        view()->share(['layout' => config('site_config_package.layout')]);
    }

    public function index(Request $request)
    {
        $list = Collection::make(config('site_config'))
            ->dot()
            ->map(function ($item, $key) {
                return ['key' => $key, 'value' => is_bool($item) ? (int) $item : (string) $item];
            })->merge(SiteConfig::select(['id', 'key', 'value', 'type'])->get()->keyBy('key'))
            ->sortBy('key')->values();

        $types = SiteConfig::allowedTypes();

        return view('site_config::vue')->with(compact('list', 'types'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', SiteConfig::class);

        $this->validate($request, [
            'key'  => 'required',
        ]);

        $siteConfig = SiteConfig::updateOrCreate($request->only('key'), $request->all());

        return $siteConfig->toArray();
    }
}
