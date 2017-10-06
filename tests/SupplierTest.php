<?php

use App\Contact;
use App\Supplier;
use Illuminate\Http\Response;

class SupplierTest extends TestCase
{

    public function testShouldFindAllSuppliersRequest()
    {
        $supplierQuantity = 10;
        $supplierList = factory(Supplier::class)->times($supplierQuantity)->create();
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
                            Supplier::NAME,
                            Supplier::TRADE_NAME,
                            Supplier::LEGAL_DOCUMENT_CODE,
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
            ->seeJson([Supplier::NAME => $supplier->getAttribute(Supplier::NAME)])
            ->seeJson([Supplier::TRADE_NAME => $supplier->getAttribute(Supplier::TRADE_NAME)])
            ->seeJson([Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(Supplier::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->make();
        $supplierArray = $this->convertObjectToArray($supplier);
        $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson([Supplier::NAME => $supplier->getAttribute(Supplier::NAME)])
            ->seeJson([Supplier::TRADE_NAME => $supplier->getAttribute(Supplier::TRADE_NAME)])
            ->seeJson([Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(Supplier::LEGAL_DOCUMENT_CODE)]);
    }

    public function testShouldCreateSupplierWithContactRequest()
    {
        $contactQuantity = 3;
        $contacts = factory(Contact::class)->times($contactQuantity)->make();
        $supplier = factory(Supplier::class)->make();
        $supplier->setAttribute(Supplier::RELATIONSHIP_CONTACTS, $contacts);
        $supplierArray = $this->convertObjectToArray($supplier);
        $httpRequest = $this->json('POST', '/api/suppliers', $supplierArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson([Supplier::NAME => $supplier->getAttribute(Supplier::NAME)])
            ->seeJson([Supplier::TRADE_NAME => $supplier->getAttribute(Supplier::TRADE_NAME)])
            ->seeJson([Supplier::LEGAL_DOCUMENT_CODE => $supplier->getAttribute(Supplier::LEGAL_DOCUMENT_CODE)]);
        foreach ($supplier->getAttribute(Supplier::RELATIONSHIP_CONTACTS) as $contact) {
            $httpRequest->seeJson([Contact::PHONE => $contact->phone]);
            $httpRequest->seeJson([Contact::ADDRESS => $contact->address]);
            $httpRequest->seeJson([Contact::ADDRESS_COMPLEMENT => $contact->address_complement]);
            $httpRequest->seeJson([Contact::POSTAL_CODE => $contact->postal_code]);
            $httpRequest->seeJson([Contact::CITY => $contact->city]);
            $httpRequest->seeJson([Contact::REGION => $contact->region]);
            $httpRequest->seeJson([Contact::COUNTRY => $contact->country]);
        }
    }

    public function testShouldUpdateSupplierRequest()
    {
        $supplier = factory(App\Supplier::class)->create();
        $supplier->setAttribute(Supplier::NAME, 'This is an updated field');
        $supplier->setAttribute(Supplier::TRADE_NAME, 'This is another updated field');
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