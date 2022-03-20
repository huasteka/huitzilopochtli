<?php
namespace App\Schemas;

use App\DeliveryAddress;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

class DeliveryAddressSchema extends BaseSchema
{
    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'delivery_addresses';
    }
    
    /**
     * Get resource identity.
     *
     * @param DeliveryAddress $resource
     *
     * @return string
     */
    public function getId($resource): ?string
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
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            DeliveryAddress::IS_DEFAULT => $resource->getAttribute(DeliveryAddress::IS_DEFAULT),
            DeliveryAddress::RELATIONSHIP_CONTACTS => $resource->contacts()->first(),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param DeliveryAddress $resource
     *
     * @return array
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [];
    }

}
