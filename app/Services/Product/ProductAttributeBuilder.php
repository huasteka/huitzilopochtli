<?php

namespace App\Services\Product;

use App\Services\AttributeBuilder;

trait ProductAttributeBuilder
{

    use AttributeBuilder;

    protected static $requestAttributeMerchandise = 'merchandise';

    protected function getMerchandiseProperty($property)
    {
        return $this->property(static::$requestAttributeMerchandise, $property);
    }

}
