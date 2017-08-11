<?php
namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

abstract class ContactableController extends Controller
{
    
    const REQUEST_ATTRIBUTE_CONTACTS = 'contacts';

    protected function createContactsFromRequest(Request $request)
    {
        $this->validateContactsRequest($request);
        $contactList = [];
        foreach ($request->get(self::REQUEST_ATTRIBUTE_CONTACTS) as $contactRequest) {
            $contactList[] = $this->createContact($contactRequest);
        }
        return $contactList;
    }

    protected function createContact($requestParams)
    {
        return (new Contact())->fill(Contact::readAttributes($requestParams));
    }

    protected function validateContactsRequest(Request $request)
    {
        $this->validate($request, [
            'contacts.*.phone' => 'required',
            'contacts.*.address' => 'required',
            'contacts.*.address_complement' => 'required',
            'contacts.*.postal_code' => 'required',
            'contacts.*.city' => 'required',
            'contacts.*.region' => 'required',
            'contacts.*.country' => 'required',
        ]);
    }

}