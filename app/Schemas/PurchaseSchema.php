<?php
namespace App\Schemas;

use App\Merchandise;
use App\Purchase;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

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
 * @apiSuccess {Number} data.relationships.delivery.links
 * @apiSuccess {Number} data.relationships.delivery.links.related
 * @apiSuccess {Object} data.relationships.merchandisesPurchased
 * @apiSuccess {Object[]} data.relationships.merchandisesPurchased.data
 * @apiSuccess {Object} data.relationships.merchandisesPurchased.data.type
 * @apiSuccess {Number} data.relationships.merchandisesPurchased.data.id
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 * 
 * @apiSuccess {Object[]} included
 * @apiUSe ResponseMerchandisePurchaseJson
 * 
 */
class PurchaseSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'purchases';
    }

    /**
     * Get resource identity.
     *
     * @param Purchase $resource
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
     * @param Purchase $resource
     *
     * @return array
     */
    public function getAttributes($resource, ContextInterface $context): iterable
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
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            Purchase::RELATIONSHIP_DELIVERY => [
                self::RELATIONSHIP_DATA => $resource->delivery()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => true,
            ],
            Purchase::RELATIONSHIP_MERCHANDISES_PURCHASED => [
                self::RELATIONSHIP_DATA => $resource->merchandisesPurchased()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
        ];
    }
    
}