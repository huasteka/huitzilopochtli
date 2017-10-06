<?php

use App\Product;
use Illuminate\Http\Response;

class ProductTest extends TestCase
{

    public function testShouldFindAllProductsRequest()
    {
        $productQuantity = 10;
        $productList = factory(Product::class)->times($productQuantity)->create();
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
                            Product::NAME,
                            Product::CODE,
                            Product::DESCRIPTION,
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
                Product::NAME => $product->getAttribute(Product::NAME),
                Product::CODE => $product->getAttribute(Product::CODE),
                Product::DESCRIPTION => $product->getAttribute(Product::DESCRIPTION),
            ]]);
    }

    public function testShouldCreateProductRequest()
    {
        $product = factory(App\Product::class)->make();
        $productArray = $this->convertObjectToArray($product);
        $this->json('POST', '/api/products', $productArray)
            ->seeStatusCode(Response::HTTP_CREATED)
            ->seeJson([Product::NAME => $product->getAttribute(Product::NAME)])
            ->seeJson([Product::CODE => $product->getAttribute(Product::CODE)])
            ->seeJson([Product::DESCRIPTION => $product->getAttribute(Product::DESCRIPTION)]);
    }

    public function testShouldUpdateProductRequest()
    {
        $product = factory(App\Product::class)->create();
        $product->setAttribute(Product::NAME, 'This is an updated field');
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
