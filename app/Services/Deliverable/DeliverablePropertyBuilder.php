<?php

namespace App\Services\Deliverable;

use App\Services\PropertyBuilder;

trait DeliverablePropertyBuilder
{

    use PropertyBuilder;

    protected static $requestAttributeMerchandises = 'merchandises';
    protected static $requestAttributeMerchandisesSupplierId = 'supplier_id';
    protected static $requestAttributeMerchandisesClientId = 'client_id';
    protected static $requestAttributeDelivery = 'delivery';
    protected static $requestAttributeDeliveryAddress = 'delivery_address';
    protected static $requestAttributeDeliveryAddressId = 'delivery_address_id';
    protected static $requestAttributeContacts = 'contacts';

    protected function getMerchandiseProperty($merchandiseProperty)
    {
        return $this->getMerchandisePropertyAt($merchandiseProperty, '*');
    }
    
    protected function getMerchandisePropertyAt($merchandiseProperty, $indexAt) {
        return $this->property(static::$requestAttributeMerchandises, $indexAt, $merchandiseProperty);
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
