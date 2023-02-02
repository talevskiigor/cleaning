<?php

namespace Unknown\Cleaning\Classes\Actions;

use Exception;

class BaseCleaning
{

    public function __get($key)
    {
        if (!property_exists($this, $key)) {
            throw new Exception('Child class ' . get_called_class() . ' failed to define public ' . $key . ' property');
        }

        return static::$$key;
    }

    protected function getoutput(): array
    {
        return [
            'action' => $this->action->name,
            'time' => $this->time
        ];

    }

}
