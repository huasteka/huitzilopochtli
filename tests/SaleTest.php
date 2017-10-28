<?php

class SaleTest extends DeliverableTest
{

    public function testShouldFindAllSalesRequest()
    {
        $saleQuantity = 10;
        $salesList = $this->createSale($saleQuantity);
        $salesList = $this->convertObjectToArray($salesList);
        assertThat(count($salesList), equalTo($saleQuantity));
        foreach ($salesList as $saleInDatabase) {
            $this->seeInDatabase('sales', $saleInDatabase);
        }
        $this->json('GET', '/api/sales')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\Sale::CODE,
                            App\Sale::DESCRIPTION,
                            App\Sale::GROSS_VALUE,
                            App\Sale::NET_VALUE,
                            App\Sale::DISCOUNT,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneSaleRequest()
    {
        $sale = $this->createSale(1)->first();
        $this->json('GET', "/api/sales/{$sale->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\Sale::CODE => $sale->getAttribute(App\Sale::CODE)])
            ->seeJson([App\Sale::DESCRIPTION => $sale->getAttribute(App\Sale::DESCRIPTION)])
            ->seeJson([App\Sale::GROSS_VALUE => $sale->getAttribute(App\Sale::GROSS_VALUE)])
            ->seeJson([App\Sale::NET_VALUE => $sale->getAttribute(App\Sale::NET_VALUE)])
            ->seeJson([App\Sale::DISCOUNT => $sale->getAttribute(App\Sale::DISCOUNT)]);
    }

    public function testShouldCreateSaleRequest()
    {
        $delivery = $this->createDelivery();
        $saleRequest = $this->createSaleRequest($delivery);
        $this->assertThatSalePostIsValid($saleRequest);
    }

    public function testShouldCreateSaleAndDeliveryRequest()
    {
        $delivery = $this->createDeliveryWithAddressAndContacts();
        $saleRequest = $this->createSaleRequest($delivery);
        $this->assertThatSalePostIsValid($saleRequest);
    }

    private function assertThatSalePostIsValid(array $saleRequest)
    {
        $request = $this->json('POST', '/api/sales', $saleRequest)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Sale::CODE => $saleRequest[App\Sale::CODE]])
            ->seeJson([App\Sale::DESCRIPTION => $saleRequest[App\Sale::DESCRIPTION]])
            ->seeJson([App\Sale::GROSS_VALUE => $saleRequest[App\Sale::GROSS_VALUE]])
            ->seeJson([App\Sale::DISCOUNT => $saleRequest[App\Sale::DISCOUNT]])
            ->seeJson([App\Sale::NET_VALUE => $saleRequest[App\Sale::NET_VALUE]])
            ->seeInDatabase('sales',  [
                App\Sale::CODE => $saleRequest[App\Sale::CODE]
            ])
            ->seeInDatabase('deliveries',  [
                App\Delivery::SENT_AT => $saleRequest['delivery'][App\Delivery::SENT_AT],
                App\Delivery::ARRIVED_AT => $saleRequest['delivery'][App\Delivery::ARRIVED_AT],
                App\Delivery::DELIVERY_TIME => $saleRequest['delivery'][App\Delivery::DELIVERY_TIME],
            ]);
        foreach ($saleRequest['merchandises'] as $merchandise) {
            $request->seeInDatabase('merchandise_sale', [
                'merchandise_id' => $merchandise['id']
            ]);
        }
    }

    public function testShouldDestroySaleRequest()
    {
        $sale = $this->createSale(1)->first();
        $this->json('DELETE', "/api/sales/{$sale->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('sales', [
                'id' => $sale->getKey(),
                'code' => $sale->getAttribute(App\Sale::CODE),
            ]);
    }

    /**
     * @param $amount
     * @return Illuminate\Database\Eloquent\Collection
     */
    private function createSale($amount)
    {
        return factory(App\Sale::class)
            ->times($amount)
            ->create()
            ->each(function (App\Sale $sale) {
                $sale->delivery()->save($this->createDelivery());
                $merchandiseList = $this->createMerchandises(5);
                foreach ($merchandiseList as $merchandise) {
                    $sale->merchandises()->save($merchandise, [
                        App\MerchandiseSale::RETAIL_PRICE => mt_rand(1, 99999),
                        App\MerchandiseSale::QUANTITY => mt_rand(1, 99),
                    ]);
                }
            });
    }

    private function createSaleRequest(App\Delivery $delivery)
    {
        $sale = factory(App\Sale::class)->make();
        $sale->setAttribute('delivery', $delivery);
        $saleRequest = $this->convertObjectToArray($sale);
        $saleRequest['merchandises'] = [];

        $merchandises = $this->createMerchandises(5);
        foreach ($merchandises as $merchandise) {
            $saleRequest['merchandises'][] = [
                'id' => $merchandise->getKey(),
                'retail_price' => mt_rand(1, 99999),
                'quantity' => mt_rand(1, 99),
            ];
        }
        return $saleRequest;
    }

}
