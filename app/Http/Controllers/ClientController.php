<?php
namespace App\Http\Controllers;

use App\Client;
use App\Contact;
use App\Schemas\ClientSchema;
use App\Schemas\ContactSchema;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends ContactableController
{

    private $clientService;

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Client::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getClientService()->getValidationRulesOnCreate($request));
        $client = $this->getClientService()->store($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($client), Response::HTTP_CREATED);
    }

    public function show($clientId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Client::find($clientId)));
    }

    public function update(Request $request, $clientId)
    {
        return $this->findClientAndExecuteCallback($clientId, function (Client $client) use ($request) {
            $this->validateRequest($request, $this->getClientService()->getValidationRulesOnUpdate($request));
            $this->getClientService()->update($request, $client);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function destroy($clientId)
    {
        return $this->findClientAndExecuteCallback($clientId, function (Client $client) {
            $client->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function storeContact(Request $request, $clientId)
    {
        return $this->storeContactHandler($request, $this->getClientService(), function (callable $createContact) use ($clientId) {
            return $this->findClientAndExecuteCallback($clientId, function (Client $client) use ($createContact) {
                return $createContact($client);
            });
        });
    }

    public function updateContact(Request $request, $clientId, $contactId)
    {
        return $this->updateContactHandler($request, $this->getClientService(), $contactId, function (callable $updateContact) use ($clientId) {
            return $this->findClientAndExecuteCallback($clientId, function () use ($updateContact) {
                return $updateContact();
            });
        });
    }

    public function destroyContact(Request $request, $clientId, $contactId)
    {
        return $this->destroyContactHandler($request, $contactId, function (callable $destroyContact) use ($clientId) {
            return $this->findClientAndExecuteCallback($clientId, function () use ($destroyContact) {
                return $destroyContact();
            });
        });
    }

    private function findClientAndExecuteCallback($clientId, callable $callback)
    {
        $client = Client::find($clientId);
        if (is_null($client)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($client);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Client::class => ClientSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

    private function getClientService()
    {
        if (is_null($this->clientService)) {
            $this->clientService = new ClientService();
        }
        return $this->clientService;
    }

}
