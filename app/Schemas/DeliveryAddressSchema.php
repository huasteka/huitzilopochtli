<?php
namespace App\Schemas;


use App\DeliveryAddress;
use Neomerx\JsonApi\Schema\SchemaProvider;

class DeliveryAddressSchema extends SchemaProvider
{

    protected $resourceType = 'delivery_addresses';
    
    /**
     * Get resource identity.
     *
     * @param DeliveryAddress $resource
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
     * @param DeliveryAddress $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            DeliveryAddress::IS_DEFAULT => $resource->getAttribute(DeliveryAddress::IS_DEFAULT),
            DeliveryAddress::RELATIONSHIP_CONTACTS => $resource->contacts()->first(),
        ];
    }

}
