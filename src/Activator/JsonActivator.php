<?php

namespace DNT\LaravelModule\Activator;

use DNT\Json\Jsonable;
use DNT\LaravelModule\Contracts\Module;
use DNT\LaravelModule\Contracts\ModuleActivator;
use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Enums\ModuleStatus;
use Illuminate\Support\Traits\Macroable;

class JsonActivator implements ModuleActivator
{
    use Macroable;

    private Jsonable $jsonable;

    private ModuleLoader $loader;

    public function __construct(ModuleLoader $loader, Jsonable $jsonable)
    {
        $this->loader = $loader;
        $this->jsonable = $jsonable;
    }

    public function enable(Module|string $module): void
    {
        $moduleID = $this->loader->findModule($module);
        if ($this->isStatus($module, ModuleStatus::ENABLE)) {
            return;
        }
        $this->jsonable->set($moduleID->id(), true)->save();
    }

    public function isStatus(Module|string $module, ModuleStatus $status): bool
    {
        $moduleID = $this->loader->findModule($module);
        return $this->getStatus($moduleID) == $status;
    }

    public function getStatus(Module|string $module): ModuleStatus
    {
        $moduleID = $this->loader->findModule($module);
        return ModuleStatus::from((int)$this->jsonable->get($moduleID->id(), false));
    }

    public function disable(Module|string $module): void
    {
        $moduleID = $this->loader->findModule($module);
        $this->jsonable->set($moduleID->id(), false)->save();
    }
}