<?php

namespace App\Services;

trait PropertyBuilder
{

    protected static $requestAttributeId = 'id';

    protected function property(...$args)
    {
        return implode('.', $args);
    }
    
}
