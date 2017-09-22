<?php

use Illuminate\Http\Response;

class ProductTest extends TestCase
{

    public function testShouldFindAllProductsRequest()
    {
        $productQuantity = 10;
        $productList = factory(App\Product::class)->times($productQuantity)->create();
        $productList = $this->convertObjectToArray($productList);
        assertThat(count($productList), equalTo($productQuantity));
        foreach ($productList as $productInDatabase) {
            $this->seeInDatabase('products', $productInDatabase);
        }
        $this->json('GET', '/api/products')
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJsonStructure([
                'data' => [
                    '*' => [
                        'attributes' => [
                            'name',
                            'code',
                            'description',
                            'retail_price',
                            'purchase_price'
                        ]
                    ]
                ]
            ]);
    }

    public function testShouldFindOneProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $this->json('GET', "/api/products/{$product->getKey()}")
            ->seeStatusCode(Response::HTTP_OK)
            ->seeJson(['attributes' => [
                'name' => $product->getAttribute('name'),
                'code' => $product->getAttribute('code'),
                'description' => $product->getAttribute('description'),
                'retail_price' => $product->getAttribute('retail_price'),
                'purchase_price' => $product->getAttribute('purchase_price'),
            ]]);
    }

    public function testShouldCreateProductRequest()
    {
        $product = factory(App\Product::class)->make();
        $productArray = $this->convertObjectToArray($product);
        $this->json('POST', '/api/products', $productArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson(['name' => $product->getAttribute('name')])
            ->seeJson(['code' => $product->getAttribute('code')])
            ->seeJson(['description' => $product->getAttribute('description')])
            ->seeJson(['retail_price' => $product->getAttribute('retail_price')])
            ->seeJson(['purchase_price' => $product->getAttribute('purchase_price')]);
    }

    public function testShouldUpdateProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $product->setAttribute('name', 'This is an updated field');
        $productArray = $this->convertObjectToArray($product);
        $this->json('PUT', "/api/products/{$product->getKey()}", $productArray)
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('products', $productArray);
    }

    public function testShouldDestroyProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $productArray = $this->convertObjectToArray($product);
        $this->json('DELETE', "/api/products/{$product->getKey()}")
            ->seeStatusCode(Response::HTTP_NO_CONTENT)
            ->seeInDatabase('products', $productArray);
    }

}
