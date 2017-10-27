<?php

namespace App\Services\DeliveryAddress;

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
        return array_merge(
            [DeliveryAddress::IS_DEFAULT => 'required|boolean'],
            $this->getValidationRulesForContacts($request)
        );
    }

}
