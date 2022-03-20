<?php
namespace App\Schemas;

use App\Merchandise;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @apiDefine ResponseMerchandiseJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {String} data.attributes.retail_price
 * @apiSuccess {String} data.attributes.purchase_price
 * @apiSuccess {Object} data.relationships
 * @apiSuccess {Object} data.relationships.product
 * @apiSuccess {Object[]} data.relationships.product.data
 * @apiSuccess {String} data.relationships.product.data.type
 * @apiSuccess {Number} data.relationships.product.data.id
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 * @apiSuccess {Object[]} included
 * @apiSuccess {String} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {String} included.attributes.name
 * @apiSuccess {String} included.attributes.code
 * @apiSuccess {String} included.attributes.description
 */
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