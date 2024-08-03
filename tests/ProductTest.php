<?php

class ProductTest extends TestCase
{

    public function testShouldFindAllProductsRequest()
    {
        $productQuantity = 10;
        $productList = factory(App\Product::class)->times($productQuantity)->create();
        $productList = $this->convertObjectToArray($productList);
        $this->assertThat(count($productList), $this->equalTo($productQuantity));
        foreach ($productList as $productInDatabase) {
            $this->seeInDatabase('products', $productInDatabase);
        }
        $this->json('GET', '/api/products')
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
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

    public function testShouldFindOneProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $this->json('GET', "/api/products/{$product->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_OK)
            ->seeJson(['attributes' => [
                App\Product::NAME => $product->getAttribute(App\Product::NAME),
                App\Product::CODE => $product->getAttribute(App\Product::CODE),
                App\Product::DESCRIPTION => $product->getAttribute(App\Product::DESCRIPTION),
            ]]);
    }

    public function testShouldCreateProductRequest()
    {
        $product = factory(App\Product::class)->make();
        $productArray = $this->convertObjectToArray($product);
        $this->json('POST', '/api/products', $productArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Product::NAME => $productArray[App\Product::NAME]])
            ->seeJson([App\Product::CODE => $productArray[App\Product::CODE]])
            ->seeJson([App\Product::DESCRIPTION => $productArray[App\Product::DESCRIPTION]]);
    }

    public function testShouldCreateProductWithMerchandiseRequest()
    {
        $product = factory(App\Product::class)->make();
        $productArray = $this->convertObjectToArray($product);
        $productArray['merchandise'] = $this->convertObjectToArray(factory(App\Merchandise::class)->make());
        $this->json('POST', '/api/products', $productArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_CREATED)
            ->seeJson([App\Product::NAME => $productArray[App\Product::NAME]])
            ->seeJson([App\Product::CODE => $productArray[App\Product::CODE]])
            ->seeJson([App\Product::DESCRIPTION => $productArray[App\Product::DESCRIPTION]])
            ->seeInDatabase('merchandises', [
                App\Merchandise::PURCHASE_PRICE => $productArray['merchandise'][App\Merchandise::PURCHASE_PRICE],
                App\Merchandise::RETAIL_PRICE => $productArray['merchandise'][App\Merchandise::RETAIL_PRICE],
            ]);
    }

    public function testShouldUpdateProductRequest()
    {
        $updatedName = 'This is an updated field';
        $product = factory(App\Product::class)->create();
        $product->setAttribute(App\Product::NAME, $updatedName);
        $productArray = $this->convertObjectToArray($product);
        $this->json('PUT', "/api/products/{$product->getKey()}", $productArray)
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('products', [
                'id' => $product->getKey(),
                App\Product::NAME => $updatedName
            ]);
    }

    public function testShouldDestroyProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $productArray = $this->convertObjectToArray($product);
        $this->json('DELETE', "/api/products/{$product->getKey()}")
            ->seeStatusCode(Illuminate\Http\Response::HTTP_NO_CONTENT)
            ->seeInDatabase('products', $productArray);
    }

}
