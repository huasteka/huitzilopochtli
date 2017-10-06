<?php
namespace App\Schemas;

use App\Merchandise;
use Neomerx\JsonApi\Schema\SchemaProvider;

class MerchandiseSchema extends SchemaProvider
{

    protected $resourceType = 'merchandises';

    /**
     * Get resource identity.
     *
     * @param Merchandise $resource
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
     * @param Merchandise $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            Merchandise::RETAIL_PRICE => $resource->getAttribute(Merchandise::RETAIL_PRICE),
            Merchandise::PURCHASE_PRICE => $resource->getAttribute(Merchandise::PURCHASE_PRICE),
        ];
    }

    /**
     * @param Merchandise $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            Merchandise::RELATIONSHIP_PRODUCT => [
                self::DATA => function () use ($resource) {
                    return $resource->product()->getEager();
                }
            ],
        ];
    }

    public function getIncludePaths()
    {
        return [Merchandise::RELATIONSHIP_PRODUCT];
    }

}