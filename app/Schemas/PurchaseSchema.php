<?php
namespace App\Schemas;

use App\Merchandise;
use App\Purchase;
use Neomerx\JsonApi\Schema\SchemaProvider;

class PurchaseSchema extends SchemaProvider
{

    protected $resourceType = 'purchases';

    /**
     * Get resource identity.
     *
     * @param Purchase $resource
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
     * @param Purchase $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            Purchase::CODE => $resource->getAttribute(Purchase::CODE),
            Purchase::DESCRIPTION => $resource->getAttribute(Purchase::DESCRIPTION),
            Purchase::GROSS_VALUE => $resource->getAttribute(Purchase::GROSS_VALUE),
            Purchase::NET_VALUE => $resource->getAttribute(Purchase::NET_VALUE),
            Purchase::DISCOUNT => $resource->getAttribute(Purchase::DISCOUNT),
        ];
    }

    /**
     * @param Purchase $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            Purchase::RELATIONSHIP_DELIVERY => [
                self::DATA => function () use ($resource) {
                    return $resource->delivery()->getEager();
                }
            ],
            Purchase::RELATIONSHIP_MERCHANDISES => [
                self::DATA => function () use ($resource) {
                    return $resource->merchandises()->getEager();
                }
            ],
        ];
    }

    public function getIncludePaths()
    {
        return [Purchase::RELATIONSHIP_DELIVERY, Purchase::RELATIONSHIP_MERCHANDISES];
    }
    
}