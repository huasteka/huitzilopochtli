<?php
namespace App\Services;

abstract class AbstractService
{

    protected function property(...$args)
    {
        return implode('.', $args);
    }

}
