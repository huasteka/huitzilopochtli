<?php

namespace App\Schemas;

use App\Supplier;
use App\Merchandise;
use App\MerchandisePurchase;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine ResponseMerchandisePurchaseJson
 * @apiSuccess {String="merchandises_purchased"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {Number} included.attributes.purchase_price
 * @apiSuccess {Number} included.attributes.quantity
 * @apiSuccess {Object} included.relationships
 * @apiSuccess {Object} included.relationships.supplier
 * @apiSuccess {Object[]} included.relationships.supplier.data
 * @apiSuccess {String} included.relationships.supplier.data.type
 * @apiSuccess {Number} included.relationships.supplier.data.id
 * @apiSuccess {Object} included.relationships.merchandise
 * @apiSuccess {Object[]} included.relationships.merchandise.data
 * @apiSuccess {String} included.relationships.merchandise.data.type
 * @apiSuccess {Number} included.relationships.merchandise.data.id
 * @apiSuccess {Object} included.links
 * @apiSuccess {String} included.links.self
 * 
 * @apiSuccess {String="suppliers"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {String} included.attributes.name
 * @apiSuccess {String} included.attributes.trade_name
 * @apiSuccess {String} included.attributes.legal_document_code
 * @apiSuccess {Object} included.links
 * @apiSuccess {String} included.links.self
 * 
 * @apiSuccess {String="merchandises"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {Number} included.attributes.retail_price
 * @apiSuccess {Number} included.attributes.purchase_price
 * @apiSuccess {Object} included.relationships
 * @apiSuccess {Object} included.relationships.product
 * @apiSuccess {Object} included.relationships.product.data
 * @apiSuccess {String} included.relationships.product.data.type
 * @apiSuccess {Number} included.relationships.product.data.id
 * @apiSuccess {Object} included.links
 * @apiSuccess {String} included.links.self
 * 
 * @apiSuccess {String="products"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {String} included.attributes.name
 * @apiSuccess {String} included.attributes.code
 * @apiSuccess {String} included.attributes.description
 * @apiSuccess {Object} included.links
 * @apiSuccess {String} included.links.self
 */
class MerchandisePurchaseSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'merchandises_purchased';
    }

    /**
     * Get resource identity.
     *
     * @param MerchandisePurchase $resource
     *
     * @return string
     */
    public function getId($resource): ?string
    {
        return $resource->getAttribute(MerchandisePurchase::MERCHANDISE_ID);
    }

    /**
     * Get resource attributes.
     *
     * @param MerchandisePurchase $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            MerchandisePurchase::PURCHASE_PRICE => $resource->getAttribute(MerchandisePurchase::PURCHASE_PRICE),
            MerchandisePurchase::QUANTITY => $resource->getAttribute(MerchandisePurchase::QUANTITY),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param MerchandisePurchase $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            MerchandisePurchase::RELATIONSHIP_SUPPLIER => [
                self::RELATIONSHIP_DATA => $resource->supplier()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
            MerchandisePurchase::RELATIONSHIP_MERCHANDISE => [
                self::RELATIONSHIP_DATA => $resource->merchandise()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
        ];
    }

}