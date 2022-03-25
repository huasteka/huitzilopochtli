<?php
namespace App\Schemas;

use App\DeliveryAddress;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine ResponseDeliveryAddressJson
 * @apiSuccess {String} data.type
 * @apiSuccess {Number} data.id
 * @apiSuccess {Object} data.attributes
 * @apiSuccess {Boolean} data.attributes.is_default
 * @apiSuccess {Object} data.attributes.contacts
 * @apiSuccess {String} data.attributes.contacts.phone
 * @apiSuccess {String} data.attributes.contacts.address
 * @apiSuccess {String} data.attributes.contacts.address_complement
 * @apiSuccess {String} data.attributes.contacts.postal_code
 * @apiSuccess {String} data.attributes.contacts.city
 * @apiSuccess {String} data.attributes.contacts.region
 * @apiSuccess {String} data.attributes.contacts.country
 */
class DeliveryAddressSchema extends BaseSchema
{
    /**
     * Get resource type.
     *
     * @return string
     */
    public function getType(): string
    {
        return 'delivery_addresses';
    }

    /**
     * Get resource identity.
     *
     * @param DeliveryAddress $resource
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
     * @param DeliveryAddress $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            DeliveryAddress::IS_DEFAULT => $resource->getAttribute(DeliveryAddress::IS_DEFAULT),
            DeliveryAddress::RELATIONSHIP_CONTACTS => $resource->contacts()->first(),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param DeliveryAddress $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [];
    }

}
