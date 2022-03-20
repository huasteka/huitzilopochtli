<?php
namespace App\Schemas;

use App\Merchandise;
use App\Purchase;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @apiDefine ResponsePurchaseJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {String} data.attributes.code
 * @apiSuccess {Number} data.attributes.description
 * @apiSuccess {Number} data.attributes.gross_value
 * @apiSuccess {Number} data.attributes.net_value
 * @apiSuccess {Number} data.attributes.discount
 * @apiSuccess {Object} data.relationships.delivery
 * @apiSuccess {Object[]} data.relationships.delivery.data
 * @apiSuccess {String} data.relationships.delivery.data.type
 * @apiSuccess {Number} data.relationships.delivery.data.id
 * @apiSuccess {Object} data.relationships.merchandises
 * @apiSuccess {Object[]} data.relationships.merchandises.data
 * @apiSuccess {Object} data.relationships.merchandises.data.type
 * @apiSuccess {Number} data.relationships.merchandises.data.id
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 * @apiSuccess {Object[]} included
 * @apiSuccess {String} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {String} included.attributes.name
 * @apiSuccess {String} included.attributes.code
 * @apiSuccess {String} included.attributes.description
 * @apiSuccess {String} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {Number} included.attributes.retail_price
 * @apiSuccess {Number} included.attributes.purchase_price
 * @apiSuccess {Object} included.relationships
 * @apiSuccess {Object} included.relationships.product
 * @apiSuccess {Object} included.relationships.product.data
 * @apiSuccess {Object} included.relationships.product.data.type
 * @apiSuccess {Object} included.relationships.product.data.id
 */
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
        return [
            Purchase::RELATIONSHIP_DELIVERY, 
            Purchase::RELATIONSHIP_MERCHANDISES,
            Purchase::RELATIONSHIP_MERCHANDISES . '.' . Merchandise::RELATIONSHIP_PRODUCT
        ];
    }
    
}