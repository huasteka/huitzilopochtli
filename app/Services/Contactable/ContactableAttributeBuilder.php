<?php

namespace App\Services\Contactable;

use App\Services\AttributeBuilder;

trait ContactableAttributeBuilder
{
    
    use AttributeBuilder;

    protected static $requestAttributeContacts = 'contacts';

    private function getContactsProperty($property)
    {
        return $this->property(static::$requestAttributeContacts, '*', $property);
    }

}
