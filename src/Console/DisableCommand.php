<?php

namespace DNT\LaravelModule\Console;

use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Enums\ModuleStatus;
use Illuminate\Console\Command;

class DisableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:disable {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable the specified module.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $moduleID = $this->argument('name');
        $app = $this->getLaravel();
        $module = $app->make(ModuleLoader::class)->getByID($moduleID);
        if ($module->status(ModuleStatus::DISABLE)) {
            $this->info(__('Module ":name" already disable', ['name' => $module->name()]) . '!');
            return 0;
        }
        $module->disable();
        $this->info(__('Module ":name" disable successfully', ['name' => $module->name()]) . '!');
        return 1;
    }
}