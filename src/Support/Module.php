<?php

namespace DNT\LaravelModule\Support;

use DNT\Json\Jsonable;
use DNT\LaravelModule\Contracts\Module as Contract;
use DNT\LaravelModule\Enums\ModuleStatus;

class Module implements Contract
{
    private Jsonable $jsonable;

    private ActivatorManager $activator;

    public function __construct(ActivatorManager $activator, Jsonable $jsonable)
    {
        $this->jsonable = $jsonable;
        $this->activator = $activator;
    }

    public static function make(ActivatorManager $activator, Jsonable $jsonable): self
    {
        return new static($activator, $jsonable);
    }

    public function name(): string
    {
        return $this->jsonable->get('name');
    }

    public function getPath(): string
    {
        return $this->jsonable->getPath();
    }

    public function id(): string
    {
        return $this->jsonable->get('id');
    }

    public function providers(): array
    {
        return $this->jsonable->get('providers', []);
    }

    public function version(): string
    {
        return $this->jsonable->get('version', 'dev');
    }

    public function status(ModuleStatus $status = null): bool|ModuleStatus
    {
        if (is_null($status)) {
            return $this->activator->getStatus();
        }
        return $this->activator->isStatus($this, $status);
    }
}