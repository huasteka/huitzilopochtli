<?php

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
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\Client::NAME,
                            App\Client::LEGAL_DOCUMENT_CODE,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $this->json('GET', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\Client::NAME => $client->getAttribute(App\Client::NAME)])
            ->seeJson([App\Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(App\Client::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateClientRequest()
    {
        $client = factory(App\Client::class)->make();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Client::NAME => $client->getAttribute(App\Client::NAME)])
            ->seeJson([App\Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(App\Client::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateClientWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(App\Contact::class)->times($contactQuantity)->make();
        $client = factory(App\Client::class)->make();
        $client->setAttribute(App\Client::RELATIONSHIP_CONTACTS, $contacts);
        $clientArray = $this->convertObjectToArray($client);
        $httpRequest = $this->json('POST', '/api/clients', $clientArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Client::NAME => $client->getAttribute(App\Client::NAME)])
            ->seeJson([App\Client::LEGAL_DOCUMENT_CODE => $client->getAttribute(App\Client::LEGAL_DOCUMENT_CODE)]);
        foreach ($client->getAttribute(App\Client::RELATIONSHIP_CONTACTS) as $contact) {
            $httpRequest->seeJson([App\Contact::PHONE => $contact->phone]);
            $httpRequest->seeJson([App\Contact::ADDRESS => $contact->address]);
            $httpRequest->seeJson([App\Contact::ADDRESS_COMPLEMENT => $contact->address_complement]);
            $httpRequest->seeJson([App\Contact::POSTAL_CODE => $contact->postal_code]);
            $httpRequest->seeJson([App\Contact::CITY => $contact->city]);
            $httpRequest->seeJson([App\Contact::REGION => $contact->region]);
            $httpRequest->seeJson([App\Contact::COUNTRY => $contact->country]);
        }
    }

    public function testShouldUpdateClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $client->setAttribute(App\Client::NAME, 'This is an updated field');
        $clientArray = $this->convertObjectToArray($client);
        $this->json('PUT', "/api/clients/{$client->getKey()}", $clientArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

    public function testShouldDestroyClientRequest()
    {
        $client = factory(App\Client::class)->create();
        $clientArray = $this->convertObjectToArray($client);
        $this->json('DELETE', "/api/clients/{$client->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('clients', $clientArray);
    }

}