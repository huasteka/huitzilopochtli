<?php

namespace App\Services;

trait AttributeBuilder
{

    protected static $requestAttributeId = 'id';

    protected function property(...$args)
    {
        return implode('.', $args);
    }
    
}
