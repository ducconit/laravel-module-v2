<?php

namespace DNT\LaravelModule\Console;

use DNT\LaravelModule\Contracts\ModuleLoader;
use DNT\LaravelModule\Enums\ModuleStatus;
use Illuminate\Console\Command;

class EnableCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'module:enable {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable the specified module.';

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
        if ($module->status(ModuleStatus::ENABLE)) {
            $this->info(__('Module ":name" already enabled', ['name' => $module->name()]) . '!');
            return 0;
        }
        $module->enable();
        $this->info(__('Module ":name" enable successfully', ['name' => $module->name()]) . '!');
        return 1;
    }
}