<?php

namespace App\Schemas;

use App\Contact;
use Neomerx\JsonApi\Schema\BaseSchema;
use Neomerx\JsonApi\Contracts\Schema\ContextInterface;

/**
 * @apiDefine RequestContactJson
 * @apiBody {String} phone
 * @apiBody {String} address
 * @apiBody {String} address_complement
 * @apiBody {String} postal_code
 * @apiBody {String} city
 * @apiBody {String} region
 * @apiBody {String} country
 */
class ContactSchema extends BaseSchema
{

    public function getType(): string
    {
        return 'contacts';
    }

    /**
     * Get resource identity.
     *
     * @param Contact $resource
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
     * @param Contact $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getAttributes($resource, ContextInterface $context): iterable
    {
        return [
            Contact::PHONE => $resource->getAttribute(Contact::PHONE),
            Contact::ADDRESS => $resource->getAttribute(Contact::ADDRESS),
            Contact::ADDRESS_COMPLEMENT => $resource->getAttribute(Contact::ADDRESS_COMPLEMENT),
            Contact::POSTAL_CODE => $resource->getAttribute(Contact::POSTAL_CODE),
            Contact::COUNTRY => $resource->getAttribute(Contact::COUNTRY),
            Contact::REGION => $resource->getAttribute(Contact::REGION),
            Contact::CITY => $resource->getAttribute(Contact::CITY),
        ];
    }

    /**
     * Get resource relationships.
     *
     * @param Contact $resource
     * @param ContextInterface $context
     *
     * @return array|iterable
     */
    public function getRelationships($resource, ContextInterface $context): iterable
    {
        return [];
    }

}
