<?php

namespace App\Schemas;

use App\Merchandise;
use App\Sale;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

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
 * @apiSuccess {Number} data.relationships.delivery.links
 * @apiSuccess {Number} data.relationships.delivery.links.related
 * @apiSuccess {Object} data.relationships.merchandisesSold
 * @apiSuccess {Object[]} data.relationships.merchandisesSold.data
 * @apiSuccess {Object} data.relationships.merchandisesSold.data.type
 * @apiSuccess {Number} data.relationships.merchandisesSold.data.id
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 * 
 * @apiSuccess {Object[]} included
 * @apiUSe ResponseMerchandiseSaleJson
 */
class SaleSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'sales';
    }

    /**
     * Get resource identity.
     *
     * @param Sale $resource
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
     * @param Sale $resource
     *
     * @return array
     */
    public function getAttributes($resource, ContextInterface $context): iterable
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
     * Get resource relationships.
     *
     * @param Sale $resource
     *
     * @return array
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            Sale::RELATIONSHIP_DELIVERY => [
                self::RELATIONSHIP_DATA => $resource->delivery()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => true,
            ],
            Sale::RELATIONSHIP_MERCHANDISES_SOLD => [
                self::RELATIONSHIP_DATA => $resource->merchandisesSold()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
        ];
    }

}
