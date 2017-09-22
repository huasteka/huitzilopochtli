<?php

use Illuminate\Http\Response;

class ClientTest extends TestCase
{

    public function testShouldFindAllClientsRequest()
    {
        $clientQuantity = 10;
        $clientList = factory(App\Client::class)->times($clientQuantity)->create();
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
                            'name',
                            'legal_document_code'
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $this->json('GET', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJson(['name' => $client->getAttribute('name')])
            ->seeJson(['legal_document_code' => $client->getAttribute('legal_document_code')]);
    }

    public function testShouldCreateClientRequest()
    {
        $client = factory(App\Client::class)->make();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['name' => $client->getAttribute('name')])
            ->seeJson(['legal_document_code' => $client->getAttribute('legal_document_code')]);
    }

    public function testShouldCreateClientWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(App\Contact::class)->times($contactQuantity)->make();
        $client = factory(App\Client::class)->make();
        $client->setAttribute('contacts', $contacts);
        $clientArray = $this->convertObjectToArray($client);
        $httpRequest = $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['name' => $client->getAttribute('name')])
            ->seeJson(['legal_document_code' => $client->getAttribute('legal_document_code')]);
        foreach ($client->getAttribute('contacts') as $contact) {
            $httpRequest->seeJson(['phone' => $contact->phone]);
            $httpRequest->seeJson(['address' => $contact->address]);
            $httpRequest->seeJson(['address_complement' => $contact->address_complement]);
            $httpRequest->seeJson(['postal_code' => $contact->postal_code]);
            $httpRequest->seeJson(['city' => $contact->city]);
            $httpRequest->seeJson(['region' => $contact->region]);
            $httpRequest->seeJson(['country' => $contact->country]);
        }
    }

    public function testShouldUpdateClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $client->setAttribute('name', 'This is an updated field');
        $clientArray = $this->convertObjectToArray($client);
        $this->json('PUT', "/api/clients/{$client->getKey()}", $clientArray)
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

    public function testShouldDestroyClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('DELETE', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

}