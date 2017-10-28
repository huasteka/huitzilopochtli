<?php

namespace App\Services\Product;

use App\Services\PropertyBuilder;

trait ProductPropertyBuilder
{

    use PropertyBuilder;

    protected static $requestAttributeMerchandise = 'merchandise';

    protected function getMerchandiseProperty($property)
    {
        return $this->property(static::$requestAttributeMerchandise, $property);
    }

}
