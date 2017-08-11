<?php
namespace App\Http\Controllers;


use App\Client;
use App\Services\JsonResponseFormatter;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ClientController extends ContactableController
{

    public function create(Request $request)
    {
        $this->validateRequest($request, ['legal_document_code' => 'required|unique:clients']);
        $client = Client::create($this->parseRequest($request));
        if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
            $client->contacts = $client->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJson(new JsonResponseFormatter($client), Response::HTTP_CREATED);
    }

    public function update(Request $request, $clientId)
    {
        $client = Client::find($clientId);
        if (is_null($client)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        } else {
            $this->validateRequest($request, ['legal_document_code' => 'required']);
            $client->fill($this->parseRequest($request));
            $client->save();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        }
    }

    private function validateRequest(Request $request, array $newRules = [])
    {
        $defaultRules = [
            'name' => 'required',
        ];
        $this->validate($request, array_merge($defaultRules, $newRules));
    }

    private function parseRequest(Request $request)
    {
        return Client::readAttributes($request);
    }

    public function destroy($clientId)
    {
        $client = Client::find($clientId);
        if (is_null($client)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        } else {
            $client->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        }
    }

    public function findOne($clientId)
    {
        return $this->withJson(new JsonResponseFormatter(Client::find($clientId)));
    }

    public function findAll()
    {
        return $this->withJson(new JsonResponseFormatter(Client::all()));
    }

}