<?php

use App\Client;
use App\Contact;
use Illuminate\Http\Response;

class ClientTest extends TestCase
{

    public function testShouldFindAllClientsRequest()
    {
        $clientQuantity = 10;
        $clientList = factory(Client::class)->times($clientQuantity)->create();
        $clientList = $this->convertObjectToArray($clientList);
        assertThat(count($clientList), equalTo($clientQuantity));
        foreach ($clientList as $clientInDatabase) {
            $this->seeInDatabase('clients', $clientInDatabase);
        }
        $this->json('GET', '/api/clients')
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            Client::NAME,
                            Client::LEGAL_DOCUMENT_CODE,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneClientRequest()
    {
        $client = factory(Client::class)->create();
        $this->json('GET', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJson([Client::NAME => $client->getAttribute(Client::NAME)])
            ->seeJson([Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(Client::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateClientRequest()
    {
        $client = factory(App\Client::class)->make();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson([Client::NAME => $client->getAttribute(Client::NAME)])
            ->seeJson([Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(Client::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateClientWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(Contact::class)->times($contactQuantity)->make();
        $client = factory(Client::class)->make();
        $client->setAttribute(Client::RELATIONSHIP_CONTACTS, $contacts);
        $clientArray = $this->convertObjectToArray($client);
        $httpRequest = $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson([Client::NAME => $client->getAttribute(Client::NAME)])
            ->seeJson([Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(Client::LEGAL_DOCUMENT_CODE)]);
        foreach ($client->getAttribute(Client::RELATIONSHIP_CONTACTS) as $contact) {
            $httpRequest->seeJson([Contact::PHONE => $contact->phone]);
            $httpRequest->seeJson([Contact::ADDRESS => $contact->address]);
            $httpRequest->seeJson([Contact::ADDRESS_COMPLEMENT => $contact->address_complement]);
            $httpRequest->seeJson([Contact::POSTAL_CODE => $contact->postal_code]);
            $httpRequest->seeJson([Contact::CITY => $contact->city]);
            $httpRequest->seeJson([Contact::REGION => $contact->region]);
            $httpRequest->seeJson([Contact::COUNTRY => $contact->country]);
        }
    }

    public function testShouldUpdateClientRequest()
    {
        $client = factory(Client::class)->create();
        $client->setAttribute(Client::NAME, 'This is an updated field');
        $clientArray = $this->convertObjectToArray($client);
        $this->json('PUT', "/api/clients/{$client->getKey()}", $clientArray)
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

    public function testShouldDestroyClientRequest()
    {
        $client = factory(Client::class)->create();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('DELETE', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

}