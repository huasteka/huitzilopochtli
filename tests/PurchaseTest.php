<?php

class PurchaseTest extends DeliverableTest
{

    public function testShouldFindAllPurchasesRequest()
    {
        $purchaseQuantity = 10;
        $purchasesList = $this->createPurchase($purchaseQuantity);
        $purchasesList = $this->convertObjectToArray($purchasesList);
        $this->assertThat(count($purchasesList), $this->equalTo($purchaseQuantity));
        foreach ($purchasesList as $purchaseInDatabase) {
            $this->seeInDatabase('purchases', $purchaseInDatabase);
        }
        $this->json('GET', '/api/purchases')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\Purchase::CODE,
                            App\Purchase::DESCRIPTION,
                            App\Purchase::GROSS_VALUE,
                            App\Purchase::NET_VALUE,
                            App\Purchase::DISCOUNT,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOnePurchaseRequest()
    {
        $purchase = $this->createPurchase(1)->first();
        $this->json('GET', "/api/purchases/{$purchase->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\Purchase::CODE => $purchase->getAttribute(App\Purchase::CODE)])
            ->seeJson([App\Purchase::DESCRIPTION => $purchase->getAttribute(App\Purchase::DESCRIPTION)])
            ->seeJson([App\Purchase::GROSS_VALUE => $purchase->getAttribute(App\Purchase::GROSS_VALUE)])
            ->seeJson([App\Purchase::NET_VALUE => $purchase->getAttribute(App\Purchase::NET_VALUE)])
            ->seeJson([App\Purchase::DISCOUNT => $purchase->getAttribute(App\Purchase::DISCOUNT)]);
    }

    public function testShouldCreatePurchaseRequest()
    {
        $delivery = $this->createDelivery();
        $purchaseRequest = $this->createPurchaseRequest($delivery);
        $this->assertThatPurchasePostIsValid($purchaseRequest);
    }

    public function testShouldCreatePurchaseAndDeliveryRequest()
    {
        $delivery = $this->createDeliveryWithAddressAndContacts();
        $purchaseRequest = $this->createPurchaseRequest($delivery);
        $this->assertThatPurchasePostIsValid($purchaseRequest);
    }
    
    private function assertThatPurchasePostIsValid(array $purchaseRequest)
    {
        $request = $this->json('POST', '/api/purchases', $purchaseRequest)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Purchase::CODE => $purchaseRequest[App\Purchase::CODE]])
            ->seeJson([App\Purchase::DESCRIPTION => $purchaseRequest[App\Purchase::DESCRIPTION]])
            ->seeJson([App\Purchase::GROSS_VALUE => $purchaseRequest[App\Purchase::GROSS_VALUE]])
            ->seeJson([App\Purchase::DISCOUNT => $purchaseRequest[App\Purchase::DISCOUNT]])
            ->seeJson([App\Purchase::NET_VALUE => $purchaseRequest[App\Purchase::NET_VALUE]])
            ->seeInDatabase('purchases',  [
                App\Purchase::CODE => $purchaseRequest[App\Purchase::CODE]
            ])
            ->seeInDatabase('deliveries',  [
                App\Delivery::SENT_AT => $purchaseRequest['delivery'][App\Delivery::SENT_AT],
                App\Delivery::ARRIVED_AT => $purchaseRequest['delivery'][App\Delivery::ARRIVED_AT],
                App\Delivery::DELIVERY_TIME => $purchaseRequest['delivery'][App\Delivery::DELIVERY_TIME],
            ]);
        foreach ($purchaseRequest['merchandises'] as $merchandise) {
            $request->seeInDatabase('merchandise_purchase', [
                'merchandise_id' => $merchandise['id']
            ]);
        }
    }

    public function testShouldDestroyPurchaseRequest()
    {
        $purchase = $this->createPurchase(1)->first();
        $this->json('DELETE', "/api/purchases/{$purchase->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('purchases', [
                'id' => $purchase->getKey(),
                'code' => $purchase->getAttribute(App\Purchase::CODE),
            ]);
    }

    /**
     * @param $amount
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function createPurchase($amount)
    {
        return factory(App\Purchase::class)
            ->times($amount)
            ->create()
            ->each(function (App\Purchase $purchase) {
                $purchase->delivery()->save($this->createDelivery());
                $merchandiseList = $this->createMerchandises(5);
                foreach ($merchandiseList as $merchandise) {
                    $purchase->merchandises()->save($merchandise, [
                        App\MerchandisePurchase::PURCHASE_PRICE => mt_rand(1, 99999),
                        App\MerchandisePurchase::QUANTITY => mt_rand(1, 99),
                    ]);
                }
            });
    }

    private function createPurchaseRequest(App\Delivery $delivery)
    {
        $supplierId = factory(App\Supplier::class)->create()->getKey();
        
        $purchase = factory(App\Purchase::class)->make();
        $purchase->setAttribute('delivery', $delivery);
        $purchaseRequest = $this->convertObjectToArray($purchase);
        $purchaseRequest['merchandises'] = [];

        $merchandises = $this->createMerchandises(5);
        foreach ($merchandises as $merchandise) {
            $merchandiseProperties = [
                'id' => $merchandise->getKey(),
                'purchase_price' => mt_rand(1, 99999),
                'quantity' => mt_rand(1, 99),
            ];
            if ($this->getRandomBoolean()) {
                $merchandiseProperties['supplier_id'] = $supplierId;
            }
            $purchaseRequest['merchandises'][] = $merchandiseProperties;
        }
        return $purchaseRequest;
    }

}
