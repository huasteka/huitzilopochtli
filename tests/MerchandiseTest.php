<?php

class MerchandiseTest extends TestCase
{

    public function testShouldFindAllMerchandisesRequest()
    {
        $productsQuantity = 10;
        $collection = factory(App\Product::class)
            ->times($productsQuantity)
            ->create()
            ->each(function (App\Product $product) {
                $product->merchandises()->save(factory(App\Merchandise::class)->make());
            });
        $productsList = $collection->all();
        $this->assertThat(count($productsList), $this->equalTo($productsQuantity));
        foreach ($productsList as $product) {
            $this->seeInDatabase('merchandises', $this->convertObjectToArray($product->merchandises()->first()));
        }
        $this->json('GET', '/api/merchandises')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            App\Merchandise::PURCHASE_PRICE,
                            App\Merchandise::RETAIL_PRICE,
                        ],
                    ]
                ],
                'included' => [
                    '*' => [
                        'attributes' => [
                            App\Product::NAME,
                            App\Product::CODE,
                            App\Product::DESCRIPTION,
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneMerchandiseRequest()
    {
        $merchandise = factory(App\Product::class)
            ->create()
            ->merchandises()
            ->save(factory(App\Merchandise::class)->make());
        $this->json('GET', "/api/merchandises/{$merchandise->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson([App\Merchandise::RETAIL_PRICE => $merchandise->getAttribute(App\Merchandise::RETAIL_PRICE)])
            ->seeJson([App\Merchandise::PURCHASE_PRICE => $merchandise->getAttribute(App\Merchandise::PURCHASE_PRICE)]);
    }

    public function testShouldCreateMerchandiseRequest()
    {
        $product = factory(App\Product::class)->create();
        $merchandise = factory(App\Merchandise::class)->make();
        $merchandiseArray = $this->convertObjectToArray($merchandise);
        $merchandiseArray['product_id'] = $product->getKey();
        $this->json('POST', '/api/merchandises', $merchandiseArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Merchandise::PURCHASE_PRICE => $merchandise->getAttribute(App\Merchandise::PURCHASE_PRICE)])
            ->seeJson([App\Merchandise::RETAIL_PRICE => $merchandise->getAttribute(App\Merchandise::RETAIL_PRICE)]);
    }

    public function testShouldCreateMerchandiseWithProductRequest()
    {
        $merchandise = factory(App\Merchandise::class)->make();
        $merchandiseArray = $this->convertObjectToArray($merchandise);
        $productArray = $this->convertObjectToArray(factory(App\Product::class)->make());
        $merchandiseArray['product'] = $productArray;
        $this->json('POST', '/api/merchandises', $merchandiseArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Merchandise::PURCHASE_PRICE => $merchandise->getAttribute(App\Merchandise::PURCHASE_PRICE)])
            ->seeJson([App\Merchandise::RETAIL_PRICE => $merchandise->getAttribute(App\Merchandise::RETAIL_PRICE)])
            ->seeJson([App\Product::NAME => $productArray[App\Product::NAME]])
            ->seeJson([App\Product::CODE => $productArray[App\Product::CODE]])
            ->seeJson([App\Product::DESCRIPTION => $productArray[App\Product::DESCRIPTION]]);
    }

    public function testShouldUpdateMerchandiseRequest()
    {
        $merchandise = factory(App\Product::class)
            ->create()
            ->merchandises()
            ->save(factory(App\Merchandise::class)->make());
        $merchandise->setAttribute(App\Merchandise::PURCHASE_PRICE, 666.66);
        $merchandise->setAttribute(App\Merchandise::RETAIL_PRICE, 666.66);
        $merchandiseArray = $this->convertObjectToArray($merchandise);
        $this->json('PUT', "/api/merchandises/{$merchandise->getKey()}", $merchandiseArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('merchandises', $merchandiseArray);
    }

    public function testShouldDestroyMerchandiseRequest()
    {
        $merchandise = factory(App\Product::class)
            ->create()
            ->merchandises()
            ->save(factory(App\Merchandise::class)->make());
        $merchandiseArray = $this->convertObjectToArray($merchandise);
        $this->json('DELETE', "/api/merchandises/{$merchandise->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('merchandises', $merchandiseArray);
    }
    
}
