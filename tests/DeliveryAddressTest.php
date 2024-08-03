<?php

class DeliveryAddressTest extends TestCase
{

    public function testShouldFindAllDeliveryAddresssRequest()
    {
        $deliveryAddressQuantity = 10;
        $deliveryAddressList = factory(App\DeliveryAddress::class)
            ->times($deliveryAddressQuantity)
            ->create()
            ->each(function (App\DeliveryAddress $dA) {
                $dA->contacts()->save(factory(App\Contact::class)->make());
            });
        $deliveryAddressList = $this->convertObjectToArray($deliveryAddressList);
        $this->assertThat(count($deliveryAddressList), $this->equalTo($deliveryAddressQuantity));
        foreach ($deliveryAddressList as $deliveryAddressInDatabase) {
            $this->seeInDatabase('delivery_addresses', $deliveryAddressInDatabase);
        }
        $this->json('GET', '/api/delivery_addresses')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\DeliveryAddress::IS_DEFAULT,
                            App\DeliveryAddress::RELATIONSHIP_CONTACTS => [
                                App\Contact::PHONE,
                                App\Contact::ADDRESS,
                                App\Contact::ADDRESS_COMPLEMENT,
                                App\Contact::POSTAL_CODE,
                                App\Contact::CITY,
                                App\Contact::REGION,
                                App\Contact::COUNTRY,
                            ]
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneDeliveryAddressRequest()
    {
        $deliveryAddress = factory(App\DeliveryAddress::class)->create();
        $contact = $deliveryAddress->contacts()->save(factory(App\Contact::class)->make());
        $this->json('GET', "/api/delivery_addresses/{$deliveryAddress->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\DeliveryAddress::IS_DEFAULT => $deliveryAddress->getAttribute(App\DeliveryAddress::IS_DEFAULT)])
            ->seeJson([App\Contact::PHONE => $contact->getAttribute(App\Contact::PHONE)])
            ->seeJson([App\Contact::ADDRESS => $contact->getAttribute(App\Contact::ADDRESS)])
            ->seeJson([App\Contact::ADDRESS_COMPLEMENT => $contact->getAttribute(App\Contact::ADDRESS_COMPLEMENT)])
            ->seeJson([App\Contact::POSTAL_CODE => $contact->getAttribute(App\Contact::POSTAL_CODE)])
            ->seeJson([App\Contact::CITY => $contact->getAttribute(App\Contact::CITY)])
            ->seeJson([App\Contact::REGION => $contact->getAttribute(App\Contact::REGION)])
            ->seeJson([App\Contact::COUNTRY => $contact->getAttribute(App\Contact::COUNTRY)]);
    }

    public function testShouldCreateDeliveryAddressWithContactRequest()
    {
        $contact = factory(App\Contact::class)->make();
        $contact->setAttribute(App\DeliveryAddress::IS_DEFAULT, true);
        $deliveryAddressArray = $this->convertObjectToArray($contact);
        $this->json('POST', '/api/delivery_addresses', $deliveryAddressArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([
                App\DeliveryAddress::IS_DEFAULT => $contact->getAttribute(App\DeliveryAddress::IS_DEFAULT),
                App\Contact::PHONE => $contact->phone,
                App\Contact::ADDRESS => $contact->address,
                App\Contact::ADDRESS_COMPLEMENT => $contact->address_complement,
                App\Contact::POSTAL_CODE => $contact->postal_code,
                App\Contact::CITY => $contact->city,
                App\Contact::REGION => $contact->region,
                App\Contact::COUNTRY => $contact->country,
            ]);
    }

    public function testShouldUpdateDeliveryAddressRequest()
    {
        $deliveryAddress = factory(App\DeliveryAddress::class)->create();
        $contact = $deliveryAddress->contacts()->save(factory(App\Contact::class)->make());
        $contact->setAttribute(App\Contact::ADDRESS, 'This is an updated address');
        $contact->setAttribute(App\Contact::ADDRESS_COMPLEMENT, 'This is an updated address complement');
        $contact->setAttribute(App\Contact::PHONE, '+66 66 6666-6666');
        $contact->setAttribute(App\DeliveryAddress::IS_DEFAULT, $deliveryAddress->getAttribute(App\DeliveryAddress::IS_DEFAULT));
        $contactArray = $this->convertObjectToArray($contact);
        $this->json('PUT', "/api/delivery_addresses/{$deliveryAddress->getKey()}", $contactArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('delivery_addresses', [
                'id' => $deliveryAddress->getKey(),
            ])
            ->seeInDatabase('contacts', [
                App\Contact::CONTACTABLE_ID => $deliveryAddress->getKey(),
                App\Contact::ADDRESS => $contact->getAttribute(App\Contact::ADDRESS),
                App\Contact::ADDRESS_COMPLEMENT => $contact->getAttribute(App\Contact::ADDRESS_COMPLEMENT),
                App\Contact::PHONE => $contact->getAttribute(App\Contact::PHONE),
            ]);
    }

    public function testShouldDestroyDeliveryAddressRequest()
    {
        $deliveryAddress = factory(App\DeliveryAddress::class)->create();
        $contact = $deliveryAddress->contacts()->save(factory(App\Contact::class)->make());
        $deliveryAddressArray = $this->convertObjectToArray($deliveryAddress);
        $this->json('DELETE', "/api/delivery_addresses/{$deliveryAddress->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->notSeeInDatabase('delivery_addresses', $deliveryAddressArray)
            ->notSeeInDatabase('contacts', $this->convertObjectToArray($contact));
    }

}