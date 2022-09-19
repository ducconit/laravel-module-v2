<?php

namespace Tests;

use DNT\LaravelModule\Providers\LaravelModuleServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function tearDown(): void
    {
        $this->app->make('files')->delete($this->app->make('config')->get('module.driver.json.status_file'));
    }

    protected function defineEnvironment($app)
    {
        $app->make('config')->set('module.base_path', './mocks');
        $app->make('config')->set('module.driver.json.status_file', './mocks/status.json');
        $app->register(LaravelModuleServiceProvider::class);
    }
}