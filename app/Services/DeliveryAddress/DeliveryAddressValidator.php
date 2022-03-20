<?php

namespace App\Services\DeliveryAddress;

use App\Contact;
use App\DeliveryAddress;
use App\Services\Contactable\ContactableValidator;
use Illuminate\Http\Request;

class DeliveryAddressValidator extends ContactableValidator
{

    public function getValidationRulesOnCreate(Request $request)
    {
        return $this->getValidationRulesOnCreateAndUpdate($request);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return $this->getValidationRulesOnCreateAndUpdate($request);
    }

    public function getValidationRulesOnCreateAndUpdate(Request $request)
    {
        return [
            DeliveryAddress::IS_DEFAULT => 'required|boolean',
            Contact::PHONE => 'required',
            Contact::ADDRESS => 'required',
            Contact::ADDRESS_COMPLEMENT => 'required',
            Contact::POSTAL_CODE => 'required',
            Contact::CITY => 'required',
            Contact::REGION => 'required',
            Contact::COUNTRY => 'required',
        ];
    }

}
