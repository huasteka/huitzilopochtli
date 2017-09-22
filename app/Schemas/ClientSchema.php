<?php

namespace App\Schemas;

use App\Client;
use Neomerx\JsonApi\Schema\SchemaProvider;

class ClientSchema extends SchemaProvider
{

    protected $resourceType = 'clients';

    /**
     * Get resource identity.
     *
     * @param Client $resource
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
     * @param Client $resource
     *
     * @return array
     */
    public function getAttributes($resource)
    {
        return [
            'name' => $resource->getAttribute('name'),
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
