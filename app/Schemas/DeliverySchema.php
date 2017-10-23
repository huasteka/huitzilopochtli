<?php
namespace App\Schemas;

use App\Delivery;
use Neomerx\JsonApi\Schema\SchemaProvider;

class DeliverySchema extends SchemaProvider
{

    protected $resourceType = 'deliveries';

    /**
     * Get resource identity.
     *
     * @param Delivery $resource
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
     * @param Delivery $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            Delivery::SENT_AT => $resource->getAttribute(Delivery::SENT_AT),
            Delivery::ARRIVED_AT => $resource->getAttribute(Delivery::ARRIVED_AT),
            Delivery::DELIVERY_TIME => $resource->getAttribute(Delivery::DELIVERY_TIME),
        ];
    }

    /**
     * @param Delivery $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            Delivery::RELATIONSHIP_DELIVERY_ADDRESS => [
                self::DATA => function () use ($resource) {
                    return $resource->address()->getEager();
                }
            ],
        ];
    }

    public function getIncludePaths()
    {
        return [Delivery::RELATIONSHIP_DELIVERY_ADDRESS];
    }
    
}