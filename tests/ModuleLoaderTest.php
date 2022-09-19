<?php

namespace Tests;

use DNT\LaravelModule\Contracts\ModuleLoader;
use Vendor\Package\Provider;

class ModuleLoaderTest extends TestCase
{
    public function test_scan_module()
    {
        $modules = $this->app->make(ModuleLoader::class)->all();
        $this->assertEquals(1, count($modules));
        $this->assertEquals('id-module', $modules[0]->id());
        $this->assertEquals('name module', $modules[0]->name());
        $this->assertEquals('v1.0', $modules[0]->version());
        $this->assertEquals([Provider::class], $modules[0]->providers());
    }
}