<?php

namespace DNT\LaravelModule\Providers;

use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Enums\ModuleStatus;
use DNT\LaravelModule\Support\ActivatorManager;
use DNT\LaravelModule\Support\Loader;
use DNT\LaravelModule\Support\ProviderRepository as LocalProviderRepository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\ProviderRepository;
use Illuminate\Support\Facades\App;

class LaravelModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishesConfig();
    }

    private function publishesConfig()
    {
        $this->publishes([
            $this->getConfigPath() => App::configPath('module.php')
        ], 'config');
    }

    private function getConfigPath(): string
    {
        return dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config.php';
    }

    private function registerProviders()
    {
        $this->app->register(ConsoleModuleServiceProvider::class);
    }

    public function register()
    {
        $this->mergeConfigFrom($this->getConfigPath(), 'module');

        $this->app->singleton(ModuleLoader::class, function ($app) {
            $path = $app->make('config')->get('module.base_path', $app->basePath('modules'));
            $filesystem = $app->make('files');
            return new Loader($app, $filesystem, $path);
        });

        $this->app->singleton('module.activator', function ($app) {
            return new ActivatorManager($app);
        });

        $this->registerProviders();
        $this->loadAndRegisterModules();
    }

    private function loadAndRegisterModules()
    {
        $modulesEnable = $this->app->make(ModuleLoader::class)->getByStatus(ModuleStatus::ENABLE);
        $filesystem = $this->app->make('files');
        $event = $this->app->make('events');
        $resolvedApplication = $this->app->resolved('app');
        foreach ($modulesEnable as $module) {
            $moduleID = $module->id();
            $service = $this->createProvider($resolvedApplication, $filesystem, $moduleID);
            $service->load($module->providers());
            $event->dispatch(sprintf('module.%s.loaded', $moduleID), [$module]);
        }
    }

    private function createProvider(
        bool $resolvedApplication,
        Filesystem $filesystem,
        string $moduleID
    ): LocalProviderRepository|ProviderRepository {
        if (!$resolvedApplication) {
            return new LocalProviderRepository(
                $this->app,
                $filesystem,
                $this->getCachedModulesPath($moduleID)
            );
        }

        return new ProviderRepository(
            $this->app,
            $filesystem,
            $this->getCachedModulesPath($moduleID)
        );
    }

}