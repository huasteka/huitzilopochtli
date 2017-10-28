<?php

namespace App\Services\Contactable;

use App\Contact;
use App\Services\AbstractRequestReader;
use Illuminate\Http\Request;

abstract class ContactableRequestReader extends AbstractRequestReader
{

    use ContactablePropertyBuilder;

    public function readCollection(Request $request, $type)
    {
        switch ($type) {
            case Contact::class:
                return $this->readContactCollection($request);
            default:
                return [];
        }
    }

    protected function readContactAttributes(Request $request)
    {
        return [
            Contact::PHONE => $request->get(Contact::PHONE),
            Contact::ADDRESS => $request->get(Contact::ADDRESS),
            Contact::ADDRESS_COMPLEMENT => $request->get(Contact::ADDRESS_COMPLEMENT),
            Contact::POSTAL_CODE => $request->get(Contact::POSTAL_CODE),
            Contact::CITY => $request->get(Contact::CITY),
            Contact::REGION => $request->get(Contact::REGION),
            Contact::COUNTRY => $request->get(Contact::COUNTRY),
        ];
    }

    private function readContactCollection(Request $request)
    {
        $contactCollection = [];
        foreach ($request->get(static::$requestAttributeContacts) as $contactAttributes) {
            $contactCollection[] = new Contact($contactAttributes);
        }
        return $contactCollection;
    }

}