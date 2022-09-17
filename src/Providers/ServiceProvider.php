<?php

namespace DNT\LaravelModule\Providers;

use Illuminate\Support\Env;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Illuminate\Support\Str;

abstract class ServiceProvider extends BaseServiceProvider
{
    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->app->make('config')->get(sprintf('module.%s', $key), $default);
    }

    protected function getCachedModulesPath(string $id): string
    {
        $serviceCachePath = Env::get('APP_SERVICES_CACHE', 'cache/services.php');
        return Str::replaceLast(
            $serviceCachePath,
            'cache' . DIRECTORY_SEPARATOR . $id . '_module.php',
            $this->app->getCachedServicesPath()
        );
    }
}