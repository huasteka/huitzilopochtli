<?php
namespace App\Services;

use App\Client;
use App\Contactable;
use Illuminate\Http\Request;

final class ClientService extends ContactableService
{
    
    public function store(Request $request)
    {
        $client = new Client($this->readAttributesForClient($request));
        if ($client->save() && $request->get('contacts')) {
            $client->createContacts($this->getContactCollection($request));
        }
        return $client;
    }
    
    public function update(Request $request, Client $client)
    {
        $client->update($this->readAttributesForClient($request));
    }

    public function readAttributesForClient(Request $request)
    {
        return [
            Client::NAME => $request->get(Client::NAME),
            Client::LEGAL_DOCUMENT_CODE => $request->get(Client::LEGAL_DOCUMENT_CODE),
        ];
    }

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Client::LEGAL_DOCUMENT_CODE => 'required|unique:clients'
        ]);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Client::LEGAL_DOCUMENT_CODE => 'required|exists:clients'
        ]);
    }

    private function getValidationRules(Request $request)
    {
        return array_merge([Client::NAME => 'required'], $this->getValidationRulesForContacts($request));
    }

}
