<?php

namespace App\Services\Contactable;

use App\Contact;
use App\Services\ValidatorInterface;
use Illuminate\Http\Request;

abstract class ContactableValidator implements ValidatorInterface
{
    
    use ContactableRequestChecker;

    public function getValidationRulesForContact()
    {
        return [
            Contact::PHONE => 'required',
            Contact::ADDRESS => 'required',
            Contact::ADDRESS_COMPLEMENT => 'required',
            Contact::POSTAL_CODE => 'required',
            Contact::CITY => 'required',
            Contact::REGION => 'required',
            Contact::COUNTRY => 'required',
        ];
    }
    
    protected function getValidationRulesForContacts(Request $request)
    {
        $rules = [];
        if ($this->hasContacts($request)) {
            $rules = [
                $this->getContactsProperty(Contact::PHONE) => 'required',
                $this->getContactsProperty(Contact::ADDRESS) => 'required',
                $this->getContactsProperty(Contact::ADDRESS_COMPLEMENT) => 'required',
                $this->getContactsProperty(Contact::POSTAL_CODE) => 'required',
                $this->getContactsProperty(Contact::CITY) => 'required',
                $this->getContactsProperty(Contact::REGION) => 'required',
                $this->getContactsProperty(Contact::COUNTRY) => 'required',
            ];
        }
        return $rules;
    }
    
}
