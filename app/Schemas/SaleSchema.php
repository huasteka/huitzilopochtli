<?php
namespace App\Schemas;

use App\Merchandise;
use App\Sale;
use Neomerx\JsonApi\Schema\SchemaProvider;

class SaleSchema extends SchemaProvider
{

    protected $resourceType = 'sales';

    /**
     * Get resource identity.
     *
     * @param Sale $resource
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
     * @param Sale $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            Sale::CODE => $resource->getAttribute(Sale::CODE),
            Sale::DESCRIPTION => $resource->getAttribute(Sale::DESCRIPTION),
            Sale::GROSS_VALUE => $resource->getAttribute(Sale::GROSS_VALUE),
            Sale::NET_VALUE => $resource->getAttribute(Sale::NET_VALUE),
            Sale::DISCOUNT => $resource->getAttribute(Sale::DISCOUNT),
        ];
    }

    /**
     * @param Sale $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            Sale::RELATIONSHIP_DELIVERY => [
                self::DATA => function () use ($resource) {
                    return $resource->delivery()->getEager();
                }
            ],
            Sale::RELATIONSHIP_MERCHANDISES => [
                self::DATA => function () use ($resource) {
                    return $resource->merchandises()->getEager();
                }
            ],
        ];
    }

    public function getIncludePaths()
    {
        return [
            Sale::RELATIONSHIP_DELIVERY,
            Sale::RELATIONSHIP_MERCHANDISES,
            Sale::RELATIONSHIP_MERCHANDISES . '.' . Merchandise::RELATIONSHIP_PRODUCT
        ];
    }

}
