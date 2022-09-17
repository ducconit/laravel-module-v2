<?php

namespace DNT\LaravelModule\Contracts;

use DNT\LaravelModule\Enums\ModuleStatus;

interface Module
{
    public function id(): string;

    public function name(): string;

    public function version(): string;

    public function providers(): array;

    public function getPath(): string;

    public function status(ModuleStatus $status = null): bool|ModuleStatus;
}