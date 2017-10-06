<?php

namespace App\Schemas;


use App\Product;
use Neomerx\JsonApi\Schema\SchemaProvider;

class ProductSchema extends SchemaProvider
{

    protected $resourceType = 'products';

    /**
     * Get resource identity.
     *
     * @param Product $resource
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
     * @param Product $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            Product::NAME => $resource->getAttribute(Product::NAME),
            Product::CODE => $resource->getAttribute(Product::CODE),
            Product::DESCRIPTION => $resource->getAttribute(Product::DESCRIPTION),
        ];
    }

}