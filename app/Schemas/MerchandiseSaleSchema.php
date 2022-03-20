<?php

namespace App\Schemas;

use App\Client;
use App\Merchandise;
use App\MerchandiseSale;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine ResponseMerchandiseSaleJson
 * @apiSuccess {String="merchandises_sold"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {Number} included.attributes.retail_price
 * @apiSuccess {Number} included.attributes.quantity
 * @apiSuccess {Object} included.relationships
 * @apiSuccess {Object} included.relationships.client
 * @apiSuccess {Object[]} included.relationships.client.data
 * @apiSuccess {String} included.relationships.client.data.type
 * @apiSuccess {Number} included.relationships.client.data.id
 * @apiSuccess {Object} included.relationships.merchandise
 * @apiSuccess {Object[]} included.relationships.merchandise.data
 * @apiSuccess {String} included.relationships.merchandise.data.type
 * @apiSuccess {Number} included.relationships.merchandise.data.id
 * @apiSuccess {Object} included.links
 * @apiSuccess {String} included.links.self
 * 
 * @apiSuccess {String="clients"} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {String} included.attributes.name
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
class MerchandiseSaleSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'merchandises_sold';
    }

    /**
     * Get resource identity.
     *
     * @param MerchandiseSale $resource
     *
     * @return string
     */
    public function getId($resource): ?string
    {
        return $resource->getAttribute(MerchandiseSale::MERCHANDISE_ID);
    }

    /**
     * Get resource attributes.
     *
     * @param MerchandiseSale $resource
     *
     * @return array
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            MerchandiseSale::RETAIL_PRICE => $resource->getAttribute(MerchandiseSale::RETAIL_PRICE),
            MerchandiseSale::QUANTITY => $resource->getAttribute(MerchandiseSale::QUANTITY),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param MerchandiseSale $resource
     *
     * @return array
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            MerchandiseSale::RELATIONSHIP_CLIENT => [
                self::RELATIONSHIP_DATA => $resource->client()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
            MerchandiseSale::RELATIONSHIP_MERCHANDISE => [
                self::RELATIONSHIP_DATA => $resource->merchandise()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => false,
            ],
        ];
    }

}