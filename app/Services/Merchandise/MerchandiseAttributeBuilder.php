<?php

namespace App\Services\Merchandise;

use App\Services\AttributeBuilder;

trait MerchandiseAttributeBuilder
{

    use AttributeBuilder;

    protected static $requestAttributeProduct = 'product';
    protected static $requestAttributeProductId = 'product_id';

    private function getProductProperty($property)
    {
        return $this->property(static::$requestAttributeProduct, $property);
    }

}
