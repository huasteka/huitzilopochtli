<?php

namespace App\Schemas;

use App\Product;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine ResponseProductJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {String} data.attributes.name
 * @apiSuccess {String} data.attributes.code
 * @apiSuccess {String} data.attributes.description
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 */
class ProductSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'products';
    }

    /**
     * Get resource identity.
     *
     * @param Product $resource
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
     * @param Product $resource
     *
     * @return array
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            Product::NAME => $resource->getAttribute(Product::NAME),
            Product::CODE => $resource->getAttribute(Product::CODE),
            Product::DESCRIPTION => $resource->getAttribute(Product::DESCRIPTION),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param Product $resource
     *
     * @return array
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [];
    }

}