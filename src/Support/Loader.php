<?php

namespace DNT\LaravelModule\Support;

use DNT\Json\Json;
use DNT\LaravelModule\Contracts\Module as ModuleContract;
use DNT\LaravelModule\Contracts\ModuleLoader as Contract;
use DNT\LaravelModule\Enums\ModuleStatus;
use DNT\LaravelModule\Exceptions\ModuleNotFound;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Traits\Macroable;

class Loader implements Contract
{
    use Macroable;

    /**
     * The filesystem instance.
     *
     * @var Filesystem
     */
    protected Filesystem $files;
    /**
     * Root path modules
     *
     * @var string
     */
    private string $path;
    /**
     * This array save to list modules in project
     *
     * @var array
     */
    private array $modules = [];

    /**
     * This boolean used check
     *
     * @var bool
     */
    private bool $isScanned = false;
    private array $scanPaths = [];
    private Application $container;

    public function __construct(Application $container, Filesystem $files, string $path)
    {
        $this->container = $container;
        $this->files = $files;
        $this->path = $path;
    }

    public function getByStatus(ModuleStatus $status): array
    {
        return Arr::where($this->all(), function (ModuleContract $module) use ($status) {
            return $module->status($status);
        });
    }

    public function all(): array
    {
        return $this->scan();
    }

    public function scan($force = false): array
    {
        if (!$force && $this->isScanned) {
            return $this->modules;
        }
        $modules = [];
        $scanPaths = $this->getScanPaths();
        foreach ($scanPaths as $path) {
            $files = $this->files->glob(
                $path . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "*" . DIRECTORY_SEPARATOR . "module.json"
            );
            foreach ($files as $file) {
                $module = $this->addModule($file);
                if ($module->id()) {
                    $modules[] = $module;
                }
            }
        }

        $this->isScanned = true;
        return tap($modules, function ($modules) {
            $this->modules = $modules;
        });
    }

    public function getScanPaths(): array
    {
        $this->scanPaths[] = $this->getPath();
        return array_unique($this->scanPaths);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    public function addModule(string $path): ModuleContract
    {
        return new Module($this->container->make('module.activator'), Json::make($path));
    }

    public function appendScanPath(string $path): void
    {
        $this->scanPaths[] = $path;
    }

    public function findModule(ModuleContract|string $module): ModuleContract
    {
        if ($module instanceof ModuleContract) {
            return $module;
        }
        return $this->getByID($module);
    }

    public function getByID(string $id): ModuleContract
    {
        $module = Arr::first($this->all(), function (ModuleContract $module) use ($id) {
            return $module->id() == $id;
        });
        throw_if(!$module, ModuleNotFound::fromID($id));
        return $module;
    }
}