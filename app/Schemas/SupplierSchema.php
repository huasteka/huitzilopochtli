<?php

namespace App\Schemas;

use App\Supplier;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine ResponseSupplierJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {String} data.attributes.name
 * @apiSuccess {String} data.attributes.trade_name
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
class SupplierSchema extends BaseSchema
{

    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'suppliers';
    }

    /**
     * Get resource identity.
     *
     * @param Supplier $resource
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
     * @param Supplier $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            Supplier::NAME => $resource->getAttribute(Supplier::NAME),
            Supplier::TRADE_NAME => $resource->getAttribute(Supplier::TRADE_NAME),
            Supplier::LEGAL_DOCUMENT_CODE => $resource->getAttribute(Supplier::LEGAL_DOCUMENT_CODE),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param Supplier $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [
            Supplier::RELATIONSHIP_CONTACTS => [
                self::RELATIONSHIP_DATA => $resource->contacts()->get(),
                self::RELATIONSHIP_LINKS_SELF => false,
                self::RELATIONSHIP_LINKS_RELATED => true,
            ],
        ];
    }

}
