<?php

namespace Tests;

use DNT\Json\Json;
use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Exceptions\ModuleNotFound;
use DNT\LaravelModule\Support\ActivatorManager;

class ActivatorTest extends TestCase
{
    public function test_disable_module()
    {
        $modules = $this->app->make(ModuleLoader::class)->all();
        $activator = $this->app->make(ActivatorManager::class);
        $activator->disable($modules[0]);
        $json = Json::make($activator->getConfig('driver.json.status_file'));
        $this->assertFalse($json->get($modules[0]->id()));
    }


    public function test_enable_module()
    {
        $modules = $this->app->make(ModuleLoader::class)->all();
        $activator = $this->app->make(ActivatorManager::class);
        $activator->enable($modules[0]);
        $json = Json::make($activator->getConfig('driver.json.status_file'));
        $this->assertTrue($json->get($modules[0]->id()));
    }

    public function test_disable_module_not_exists()
    {
        $this->expectException(ModuleNotFound::class);
        $activator = $this->app->make(ActivatorManager::class);
        $activator->disable('moduleidnotexists');
        $json = Json::make($activator->getConfig('driver.json.status_file'));
        $this->assertEmpty($json->all());
    }

    public function test_enable_module_not_exists()
    {
        $this->expectException(ModuleNotFound::class);
        $activator = $this->app->make(ActivatorManager::class);
        $activator->enable('moduleidnotexists');
        $json = Json::make($activator->getConfig('driver.json.status_file'));
        $this->assertEmpty($json->all());
    }
}