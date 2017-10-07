<?php
namespace App\Http\Controllers;

use App\Client;
use App\Contact;
use App\Schemas\ClientSchema;
use App\Schemas\ContactSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends ContactableController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Client::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, Client::validationRulesOnCreate());
        $client = Client::create($this->parseRequest($request));
        if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
            $client->contacts = $client->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJsonApi($this->getEncoder()->encodeData($client), Response::HTTP_CREATED);
    }

    public function show($clientId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Client::find($clientId)));
    }

    public function update(Request $request, $clientId)
    {
        return $this->findClientAndExecuteCallback($clientId, function (Client $client) use ($request) {
            $this->validateRequest($request, Client::validationRulesOnUpdate());
            $client->fill($this->parseRequest($request));
            $client->save();
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
    
    private function findClientAndExecuteCallback($clientId, callable $callback)
    {
        $client = Client::find($clientId);
        if (is_null($client)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($client);
    }

    protected function parseRequest(Request $request)
    {
        return Client::readAttributes($request);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Client::class => ClientSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

}