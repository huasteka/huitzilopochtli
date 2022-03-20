<?php

namespace App\Services\DeliveryAddress;

use App\Contact;
use App\DeliveryAddress;
use App\Services\Contactable\ContactableRequestReader;
use Illuminate\Http\Request;

class DeliveryAddressRequestReader extends ContactableRequestReader
{

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case DeliveryAddress::class:
                return $this->readAttributesForDeliveryAddress($request);
            case Contact::class:
                return $this->readAttributesForContact($request);
            default:
                return [];
        }
    }

    protected function readAttributesForDeliveryAddress(Request $request)
    {
        return [
            DeliveryAddress::IS_DEFAULT => $request->get(DeliveryAddress::IS_DEFAULT, false),
        ];
    }

    protected function readAttributesForContact(Request $request)
    {
        return [
            Contact::PHONE => $request->get(Contact::PHONE, ''),
            Contact::ADDRESS => $request->get(Contact::ADDRESS, ''),
            Contact::ADDRESS_COMPLEMENT => $request->get(Contact::ADDRESS_COMPLEMENT, ''),
            Contact::POSTAL_CODE => $request->get(Contact::POSTAL_CODE, ''),
            Contact::CITY => $request->get(Contact::CITY, ''),
            Contact::REGION => $request->get(Contact::REGION, ''),
            Contact::COUNTRY => $request->get(Contact::COUNTRY, ''),
        ];
    }

}
