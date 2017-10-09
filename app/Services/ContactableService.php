<?php
namespace App\Services;

use App\Contact;
use App\Contactable;
use Illuminate\Http\Request;

abstract class ContactableService extends AbstractService
{

    public function createContact(Request $request, Contactable $model)
    {
        $model->createContactByAttributes($this->readAttributesForContact($request));
    }

    public function updateContact(Request $request, Contact $contact)
    {
        $contact->update($this->readAttributesForContact($request));
    }

    protected function readAttributesForContact(Request $request)
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

    protected function getContactCollection(Request $request)
    {
        return $this->executeIfRequestHasContacts($request, function (Request $request) {
            $contactCollection = [];
            foreach ($request->get('contacts') as $contactArray) {
                $contactCollection[] = new Contact($this->getContactAttributes($contactArray));
            }
            return $contactCollection;
        }, []);
    }

    private function getContactAttributes(array $requestAttributes)
    {
        return [
            Contact::PHONE => $requestAttributes[Contact::PHONE],
            Contact::ADDRESS => $requestAttributes[Contact::ADDRESS],
            Contact::ADDRESS_COMPLEMENT => $requestAttributes[Contact::ADDRESS_COMPLEMENT],
            Contact::POSTAL_CODE => $requestAttributes[Contact::POSTAL_CODE],
            Contact::CITY => $requestAttributes[Contact::CITY],
            Contact::REGION => $requestAttributes[Contact::REGION],
            Contact::COUNTRY => $requestAttributes[Contact::COUNTRY],
        ];
    }

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
        return $this->executeIfRequestHasContacts($request, function () {
            return [
                $this->property('contacts', '*', Contact::PHONE) => 'required',
                $this->property('contacts', '*', Contact::ADDRESS) => 'required',
                $this->property('contacts', '*', Contact::ADDRESS_COMPLEMENT) => 'required',
                $this->property('contacts', '*', Contact::POSTAL_CODE) => 'required',
                $this->property('contacts', '*', Contact::CITY) => 'required',
                $this->property('contacts', '*', Contact::REGION) => 'required',
                $this->property('contacts', '*', Contact::COUNTRY) => 'required',
            ];
        }, []);
    }

    private function executeIfRequestHasContacts(Request $request, $callback, $default = null)
    {
        if ($request->has('contacts')) {
            return $callback($request);
        }
        return $default;
    }

}
