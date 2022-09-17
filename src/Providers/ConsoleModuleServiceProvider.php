<?php

namespace DNT\LaravelModule\Providers;

use DNT\LaravelModule\Console\DisableCommand;
use DNT\LaravelModule\Console\EnableCommand;

class ConsoleModuleServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->commands([
            EnableCommand::class,
            DisableCommand::class,
        ]);
    }
}