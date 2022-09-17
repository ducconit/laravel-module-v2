<?php

namespace DNT\LaravelModule\Contracts;

use DNT\LaravelModule\Enums\ModuleStatus;

interface ModuleActivator
{
    public function isStatus(Module|string $module, ModuleStatus $status): bool;

    public function getStatus(Module|string $module): ModuleStatus;

    public function enable(Module|string $module): void;

    public function disable(Module|string $module): void;
}