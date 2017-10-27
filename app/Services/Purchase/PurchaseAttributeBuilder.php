<?php

namespace App\Services\Purchase;

use App\Services\AttributeBuilder;

trait PurchaseAttributeBuilder
{
    
    use AttributeBuilder;

    protected static $requestAttributeMerchandises = 'merchandises';
    protected static $requestAttributeDelivery = 'delivery';
    protected static $requestAttributeDeliveryAddress = 'delivery_address';
    protected static $requestAttributeDeliveryAddressId = 'delivery_address_id';
    protected static $requestAttributeContacts = 'contacts';

    protected function getMerchandiseProperty($merchandiseProperty)
    {
        return $this->property(static::$requestAttributeMerchandises, '*', $merchandiseProperty);
    }

    protected function getDeliveryProperty($deliveryProperty)
    {
        return $this->property(static::$requestAttributeDelivery, $deliveryProperty);
    }

    protected function getDeliveryAddressProperty($deliveryAddressProperty)
    {
        return $this->property(
            static::$requestAttributeDelivery,
            static::$requestAttributeDeliveryAddress,
            $deliveryAddressProperty
        );
    }

    protected function getContactProperty($contactProperty)
    {
        return $this->property(
            static::$requestAttributeDelivery,
            static::$requestAttributeDeliveryAddress,
            static::$requestAttributeContacts,
            '*',
            $contactProperty
        );
    }

}
