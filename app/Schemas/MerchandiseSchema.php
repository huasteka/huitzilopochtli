<?php
namespace App\Schemas;

use App\Merchandise;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

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
class MerchandiseSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'merchandises';
    }

    /**
     * Get resource identity.
     *
     * @param Merchandise $resource
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
     * @param Merchandise $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            Merchandise::RETAIL_PRICE => $resource->getAttribute(Merchandise::RETAIL_PRICE),
            Merchandise::PURCHASE_PRICE => $resource->getAttribute(Merchandise::PURCHASE_PRICE),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param Merchandise $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            Merchandise::RELATIONSHIP_PRODUCT => [
                self::RELATIONSHIP_DATA => $resource->product()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => true,
            ],
        ];
    }

}