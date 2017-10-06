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
            Client::NAME => $resource->getAttribute(Client::NAME),
            Client::LEGAL_DOCUMENT_CODE => $resource->getAttribute(Client::LEGAL_DOCUMENT_CODE),
        ];
    }

    /**
     * @param Client $resource
     * @param bool $isPrimary
     * @param array $includeRelationships
     * @return array
     */
    public function getRelationships($resource, $isPrimary, array $includeRelationships)
    {
        return [
            Client::RELATIONSHIP_CONTACTS => [
                self::DATA => function () use ($resource) {
                    return $resource->contacts()->getEager();
                }
            ],
        ];
    }

    public function getIncludePaths()
    {
        return [Client::RELATIONSHIP_CONTACTS];
    }

}
