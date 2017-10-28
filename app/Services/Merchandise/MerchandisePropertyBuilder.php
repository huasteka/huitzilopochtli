<?php

namespace App\Services\Merchandise;

use App\Services\PropertyBuilder;

trait MerchandisePropertyBuilder
{

    use PropertyBuilder;

    protected static $requestAttributeProduct = 'product';
    protected static $requestAttributeProductId = 'product_id';

    private function getProductProperty($property)
    {
        return $this->property(static::$requestAttributeProduct, $property);
    }

}
