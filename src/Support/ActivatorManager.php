<?php

namespace DNT\LaravelModule\Support;

use DNT\Json\Json;
use DNT\LaravelModule\Activator\JsonActivator;
use DNT\LaravelModule\Contracts\Module as ModuleContract;
use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Enums\ModuleStatus;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Manager;

/**
 * @method enable(ModuleContract|string $module):void
 * @method disable(ModuleContract|string $module):void
 * @method isStatus(ModuleContract|string $module, ModuleStatus|string $status):bool
 * @method getStatus():ModuleStatus
 */
class ActivatorManager extends Manager
{
    public function getDefaultDriver()
    {
        return $this->getConfig('default');
    }

    public function getConfig(string $key, mixed $default = null): mixed
    {
        return $this->config->get(sprintf('module.%s', $key), $default);
    }

    public function createJsonDriver()
    {
        return new JsonActivator(
            $this->container->make(ModuleLoader::class),
            Json::make($this->getConfig('driver.json.status_file', App::storagePath('module_status.json')), true)
        );
    }
}