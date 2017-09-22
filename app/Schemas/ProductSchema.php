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
            'name' => $resource->getAttribute('name'),
            'code' => $resource->getAttribute('code'),
            'description' => $resource->getAttribute('description'),
            'retail_price' => $resource->getAttribute('retail_price'),
            'purchase_price' => $resource->getAttribute('purchase_price'),
        ];
    }

}