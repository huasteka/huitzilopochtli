<?php

namespace App\Http\Controllers;

use App\Contact;
use App\Contactable;
use App\Services\Contactable\ContactableService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

abstract class ContactableController extends RestController
{

    protected function storeContactHandler(Request $request, ContactableService $service, callable $checkRelationship)
    {
        return $checkRelationship(function (Contactable $contactable) use ($request, $service) {
            $this->validate($request, $service->validateContact());
            $service->createContact($request, $contactable);
            return $this->withStatus(Response::HTTP_CREATED);
        });
    }

    protected function updateContactHandler(Request $request, ContactableService $service, $contactId, callable $checkRelationship)
    {
        return $checkRelationship(function () use ($request, $service, $contactId) {
            return $this->findContactAndExecuteCallback($contactId, function (Contact $contact) use ($request, $service) {
                $this->validate($request, $service->validateContact());
                $service->updateContact($request, $contact);
                return $this->withStatus(Response::HTTP_NO_CONTENT);
            });
        });
    }

    protected function destroyContactHandler(Request $request, $contactId, callable $checkRelationship)
    {
        return $checkRelationship(function () use ($request, $contactId) {
            return $this->findContactAndExecuteCallback($contactId, function (Contact $contact) {
                $contact->delete();
                return $this->withStatus(Response::HTTP_NO_CONTENT);
            });
        });
    }

    private function findContactAndExecuteCallback($contactId, callable $callback)
    {
        $contact = Contact::find($contactId);
        if (is_null($contact)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($contact);
    }

}
