<?php

namespace DNT\LaravelModule\Exceptions;

use Exception;

class ModuleNotFound extends Exception
{
    public static function fromID(string $id)
    {
        return new static(__('Module :id not found', ['id' => $id]));
    }
}