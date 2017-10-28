<?php

namespace App\Services\Contactable;

use App\Services\PropertyBuilder;

trait ContactablePropertyBuilder
{
    
    use PropertyBuilder;

    protected static $requestAttributeContacts = 'contacts';

    private function getContactsProperty($property)
    {
        return $this->property(static::$requestAttributeContacts, '*', $property);
    }

}
