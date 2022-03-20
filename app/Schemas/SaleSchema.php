<?php
namespace App\Schemas;

use App\Merchandise;
use App\Sale;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @apiDefine ResponseSaleJson
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
            Sale::RELATIONSHIP_MERCHANDISES . '.' . Merchandise::RELATIONSHIP_PRODUCT,
        ];
    }

}
