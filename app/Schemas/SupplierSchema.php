<?php

namespace App\Schemas;

use App\Supplier;
use Neomerx\JsonApi\Schema\SchemaProvider;

class SupplierSchema extends SchemaProvider
{

    protected $resourceType = 'suppliers';

    /**
     * Get resource identity.
     *
     * @param Supplier $resource
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
     * @param Supplier $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'name' => $resource->getAttribute('name'),
            'trade_name' => $resource->getAttribute('trade_name'),
            'legal_document_code' => $resource->getAttribute('legal_document_code'),
        ];
    }

    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            'contacts' => [
                self::DATA => function () use ($resource) {
                    return $resource->contacts()->getEager();
                }
            ]
        ];
    }

    public function getIncludePaths()
    {
        return ['contacts'];
    }

}
