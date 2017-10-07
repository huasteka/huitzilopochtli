<?php
namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

abstract class ContactableController extends StandardController
{

    const REQUEST_ATTRIBUTE_CONTACTS = 'contacts';

    protected function createContactsFromRequest(Request $request, $toArray = false)
    {
        $this->validateContactsRequest($request);
        $contactList = [];
        foreach ($request->get(self::REQUEST_ATTRIBUTE_CONTACTS) as $contactRequest) {
            $contactList[] = $this->createContact($contactRequest, $toArray);
        }
        return $contactList;
    }

    protected function createContact($requestParams, $toArray)
    {
        $contactArray = Contact::readAttributes($requestParams);
        return ($toArray) ? $contactArray : (new Contact())->fill($contactArray);
    }

    protected function validateContactsRequest(Request $request)
    {
        $this->validate($request, [
            'contacts.*.' . Contact::PHONE => 'required',
            'contacts.*.' . Contact::ADDRESS => 'required',
            'contacts.*.' . Contact::ADDRESS_COMPLEMENT => 'required',
            'contacts.*.' . Contact::POSTAL_CODE => 'required',
            'contacts.*.' . Contact::CITY => 'required',
            'contacts.*.' . Contact::REGION => 'required',
            'contacts.*.' . Contact::COUNTRY => 'required',
        ]);
    }

}