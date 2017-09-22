<?php

use Illuminate\Http\Response;

class SupplierTest extends TestCase
{

    public function testShouldFindAllSuppliersRequest()
    {
        $supplierQuantity = 10;
        $supplierList = factory(App\Supplier::class)->times($supplierQuantity)->create();
        $supplierList = $this->convertObjectToArray($supplierList);
        assertThat(count($supplierList), equalTo($supplierQuantity));
        foreach ($supplierList as $supplierInDatabase) {
            $this->seeInDatabase('suppliers', $supplierInDatabase);
        }
        $this->json('GET', '/api/suppliers')
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'name', 
                            'trade_name', 
                            'legal_document_code'
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $this->json('GET', "/api/suppliers/{$supplier->getKey()}")
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJson(['name' => $supplier->getAttribute('name')])
            ->seeJson(['trade_name' => $supplier->getAttribute('trade_name')])
            ->seeJson(['legal_document_code' => $supplier->getAttribute('legal_document_code')]);
    }

    public function testShouldCreateSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->make();
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['name' => $supplier->getAttribute('name')])
            ->seeJson(['trade_name' => $supplier->getAttribute('trade_name')])
            ->seeJson(['legal_document_code' => $supplier->getAttribute('legal_document_code')]);
    }

    public function testShouldCreateSupplierWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(App\Contact::class)->times($contactQuantity)->make();
        $supplier = factory(App\Supplier::class)->make();
        $supplier->setAttribute('contacts', $contacts);
        $supplierArray = $this->convertObjectToArray($supplier);
        $httpRequest = $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['name' => $supplier->getAttribute('name')])
            ->seeJson(['trade_name' => $supplier->getAttribute('trade_name')])
            ->seeJson(['legal_document_code' => $supplier->getAttribute('legal_document_code')]);
        foreach ($supplier->getAttribute('contacts') as $contact) {
            $httpRequest->seeJson(['phone' => $contact->phone]);
            $httpRequest->seeJson(['address' => $contact->address]);
            $httpRequest->seeJson(['address_complement' => $contact->address_complement]);
            $httpRequest->seeJson(['postal_code' => $contact->postal_code]);
            $httpRequest->seeJson(['city' => $contact->city]);
            $httpRequest->seeJson(['region' => $contact->region]);
            $httpRequest->seeJson(['country' => $contact->country]);
        }
    }

    public function testShouldUpdateSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $supplier->setAttribute('name', 'This is an updated field');
        $supplier->setAttribute('trade_name', 'This is another updated field');
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('PUT', "/api/suppliers/{$supplier->getKey()}", $supplierArray)
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('suppliers', $supplierArray);
    }

    public function testShouldDestroySupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('DELETE', "/api/suppliers/{$supplier->getKey()}")
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('suppliers', $supplierArray);
    }

}