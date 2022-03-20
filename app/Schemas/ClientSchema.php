<?php

namespace App\Schemas;

use App\Client;
use Neomerx\JsonApi\Schema\SchemaProvider;

/**
 * @apiDefine ResponseClientJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {String} data.attributes.name
 * @apiSuccess {String} data.attributes.legal_document_code
 * @apiSuccess {Object} data.relationships
 * @apiSuccess {Object} data.relationships.contacts
 * @apiSuccess {Object[]} data.relationships.contacts.data
 * @apiSuccess {String} data.relationships.contacts.data.type
 * @apiSuccess {Number} data.relationships.contacts.data.id
 * @apiSuccess {Object} data.links
 * @apiSuccess {String} data.links.self
 * @apiSuccess {Object[]} included
 * @apiSuccess {String} included.type
 * @apiSuccess {Number} included.id
 * @apiSuccess {Object} included.attributes
 * @apiSuccess {String} included.attributes.phone
 * @apiSuccess {String} included.attributes.address
 * @apiSuccess {String} included.attributes.address_complement
 * @apiSuccess {String} included.attributes.postal_code
 * @apiSuccess {String} included.attributes.country
 * @apiSuccess {String} included.attributes.region
 * @apiSuccess {String} included.attributes.city
 */
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
