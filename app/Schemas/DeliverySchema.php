<?php
namespace App\Schemas;

use App\Delivery;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

class DeliverySchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'deliveries';
    }

    /**
     * Get resource identity.
     *
     * @param Delivery $resource
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
     * @param Delivery $resource
     *
     * @return array
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            Delivery::SENT_AT => $resource->getAttribute(Delivery::SENT_AT),
            Delivery::ARRIVED_AT => $resource->getAttribute(Delivery::ARRIVED_AT),
            Delivery::DELIVERY_TIME => $resource->getAttribute(Delivery::DELIVERY_TIME),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param Delivery $resource
     *
     * @return array
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            Delivery::RELATIONSHIP_DELIVERY_ADDRESS => [
                self::RELATIONSHIP_DATA => $resource->address()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => true,
            ],
        ];
    }
    
}