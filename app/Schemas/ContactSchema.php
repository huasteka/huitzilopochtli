<?php

namespace App\Schemas;

use App\Contact;
use Neomerx\JsonApi\Schema\SchemaProvider;

class ContactSchema extends SchemaProvider
{

    protected $resourceType = 'contacts';

    /**
     * Get resource identity.
     *
     * @param Contact $resource
     *
     * @return string
     */
    public function getId($resource)
    {
        return $resource->getKey();
    }

    /**
     * Get resource attributes.
     *
     * @param Contact $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'phone' => $resource->getAttribute('phone'),
            'address' => $resource->getAttribute('address'),
            'address_complement' => $resource->getAttribute('address_complement'),
            'postal_code' => $resource->getAttribute('postal_code'),
            'city' => $resource->getAttribute('city'),
            'region' => $resource->getAttribute('region'),
            'country' => $resource->getAttribute('country'),
        ];
    }

}
