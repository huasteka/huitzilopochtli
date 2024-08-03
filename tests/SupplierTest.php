<?php

class SupplierTest extends TestCase
{

    public function testShouldFindAllSuppliersRequest()
    {
        $supplierQuantity = 10;
        $supplierList = factory(App\Supplier::class)->times($supplierQuantity)->create();
        $supplierList = $this->convertObjectToArray($supplierList);
        $this->assertThat(count($supplierList), $this->equalTo($supplierQuantity));
        foreach ($supplierList as $supplierInDatabase) {
            $this->seeInDatabase('suppliers', $supplierInDatabase);
        }
        $this->json('GET', '/api/suppliers')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\Supplier::NAME,
                            App\Supplier::TRADE_NAME,
                            App\Supplier::LEGAL_DOCUMENT_CODE,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $this->json('GET', "/api/suppliers/{$supplier->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\Supplier::NAME => $supplier->getAttribute(App\Supplier::NAME)])
            ->seeJson([App\Supplier::TRADE_NAME => $supplier->getAttribute(App\Supplier::TRADE_NAME)])
            ->seeJson([App\Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(App\Supplier::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->make();
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Supplier::NAME => $supplier->getAttribute(App\Supplier::NAME)])
            ->seeJson([App\Supplier::TRADE_NAME => $supplier->getAttribute(App\Supplier::TRADE_NAME)])
            ->seeJson([App\Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(App\Supplier::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateSupplierWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(App\Contact::class)->times($contactQuantity)->make();
        $supplier = factory(App\Supplier::class)->make();
        $supplier->setAttribute(App\Supplier::RELATIONSHIP_CONTACTS, $contacts);
        $supplierArray = $this->convertObjectToArray($supplier);
        $httpRequest = $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Supplier::NAME => $supplier->getAttribute(App\Supplier::NAME)])
            ->seeJson([App\Supplier::TRADE_NAME => $supplier->getAttribute(App\Supplier::TRADE_NAME)])
            ->seeJson([App\Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(App\Supplier::LEGAL_DOCUMENT_CODE)]);
        foreach ($supplier->getAttribute(App\Supplier::RELATIONSHIP_CONTACTS) as $contact) {
            $httpRequest->seeJson([App\Contact::PHONE => $contact->phone]);
            $httpRequest->seeJson([App\Contact::ADDRESS => $contact->address]);
            $httpRequest->seeJson([App\Contact::ADDRESS_COMPLEMENT => $contact->address_complement]);
            $httpRequest->seeJson([App\Contact::POSTAL_CODE => $contact->postal_code]);
            $httpRequest->seeJson([App\Contact::CITY => $contact->city]);
            $httpRequest->seeJson([App\Contact::REGION => $contact->region]);
            $httpRequest->seeJson([App\Contact::COUNTRY => $contact->country]);
        }
    }

    public function testShouldUpdateSupplierRequest()
    {
        $updatedName = 'This is an updated field';
        $updatedTradeName = 'This is another updated field';
        $supplier = factory(App\Supplier::class)->create();
        $supplier->setAttribute(App\Supplier::NAME, $updatedName);
        $supplier->setAttribute(App\Supplier::TRADE_NAME, $updatedTradeName);
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('PUT', "/api/suppliers/{$supplier->getKey()}", $supplierArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('suppliers', [
                'id' => $supplier->getKey(),
                App\Supplier::NAME => $updatedName,
                App\Supplier::TRADE_NAME => $updatedTradeName
            ]);
    }

    public function testShouldDestroySupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('DELETE', "/api/suppliers/{$supplier->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('suppliers', $supplierArray);
    }

    public function testShouldCreateContactOnSupplier()
    {
        $supplier = factory(App\Supplier::class)->create();
        $contact = factory(App\Contact::class)->make();
        $contactArray = $this->convertObjectToArray($contact);
        $this->json('POST', "/api/suppliers/{$supplier->getKey()}/contacts", $contactArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeInDatabase('contacts', $contactArray);
    }

    public function testShouldUpdateContactOnSupplier()
    {
        $contact = factory(App\Contact::class)->make();
        $supplier = factory(App\Supplier::class)->create();
        $contactId = $supplier->contacts()->save($contact)->getKey();
        $updatedCityName = 'Gothan Test City';
        $contactArray = $this->convertObjectToArray($contact);
        $contactArray[App\Contact::CITY] = $updatedCityName;
        $this->json('PUT', "/api/suppliers/{$supplier->getKey()}/contacts/{$contactId}", $contactArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('contacts', [App\Contact::CITY => $updatedCityName]);
    }

    public function testShouldDeleteContactOnSupplier()
    {
        $contact = factory(App\Contact::class)->make();
        $supplier = factory(App\Supplier::class)->create();
        $contactId = $supplier->contacts()->save($contact)->getKey();
        $this->json('DELETE', "/api/suppliers/{$supplier->getKey()}/contacts/{$contactId}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->notSeeInDatabase('contacts', ['id' => $contactId]);
    }

}