<?php

namespace DNT\LaravelModule\Activator;

use DNT\Json\Jsonable;
use DNT\LaravelModule\Contracts\Module;
use DNT\LaravelModule\Contracts\ModuleActivator;
use DNT\LaravelModule\Enums\ModuleStatus;
use Illuminate\Support\Traits\Macroable;

class JsonActivator implements ModuleActivator
{
    use Macroable;

    private Jsonable $jsonable;

    public function __construct(Jsonable $jsonable)
    {
        $this->jsonable = $jsonable;
    }

    public function enable(Module|string $module): void
    {
        if ($this->isStatus($module, ModuleStatus::ENABLE)) {
            return;
        }
        $moduleID = $this->getModuleID($module);
        $this->jsonable->set($moduleID, true)->save();
    }

    public function isStatus(Module|string $module, ModuleStatus $status): bool
    {
        $moduleID = $this->getModuleID($module);
        return $this->getStatus($moduleID) == $status;
    }

    public function getStatus(Module|string $module): ModuleStatus
    {
        $moduleID = $this->getModuleID($module);
        return ModuleStatus::from((int)$this->jsonable->get($moduleID, false));
    }

    public function disable(Module|string $module): void
    {
        $moduleID = $this->getModuleID($module);
        $this->jsonable->set($moduleID, false)->save();
    }

    public function getModuleID(Module|string $module): string
    {
        return $module instanceof Module ? $module->id() : $module;
    }
}