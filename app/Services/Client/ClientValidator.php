<?php

namespace App\Services\Client;

use App\Client;
use App\Services\Contactable\ContactableValidator;
use Illuminate\Http\Request;

class ClientValidator extends ContactableValidator
{

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRulesOnUpdate($request), [
            Client::LEGAL_DOCUMENT_CODE => 'required|unique:clients'
        ]);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge([Client::NAME => 'required'], $this->getValidationRulesForContacts($request));
    }

}
