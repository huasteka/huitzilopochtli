<?php

namespace App\Schemas;

use App\Contact;
use Neomerx\JsonApi\Schema\SchemaProvider;

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
class ContactSchema extends SchemaProvider
{

    protected $resourceType = 'contacts';

    /**
     * Get resource identity.
     *
     * @param Contact $resource
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
     * @param Contact $resource
     *
     * @return array
     */
    public function getAttributes($resource)
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

}
