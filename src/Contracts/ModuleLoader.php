<?php

namespace DNT\LaravelModule\Contracts;

use DNT\LaravelModule\Contracts\Module as ModuleContract;
use DNT\LaravelModule\Enums\ModuleStatus;

interface ModuleLoader
{
    public function getPath(): string;

    public function setPath(string $path): void;

    public function scan(): array;

    public function all(): array;

    public function getByID(string $id): ModuleContract;

    public function getByStatus(ModuleStatus $status): array;

    public function addModule(string $path): ModuleContract;

    public function getScanPaths(): array;

    public function appendScanPath(string $path): void;

    public function findModule(ModuleContract|string $module): ModuleContract;
}
