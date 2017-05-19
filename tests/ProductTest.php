<?php

class ProductTest extends TestCase
{

    use Laravel\Lumen\Testing\DatabaseMigrations;
    use Laravel\Lumen\Testing\DatabaseTransactions;

    public function testGetAllRequest()
    {
        $productQuantity = 10;
        $productList = factory(App\Product::class)->times($productQuantity)->create();
        $productList = json_decode(json_encode($productList), true);
        assertThat(count($productList), equalTo($productQuantity));
        foreach ($productList as $productInDatabase) {
            $this->seeInDatabase('products', $productInDatabase);
        }
        $this->json('GET', '/api/v1/products')
            ->seeStatusCode(200)
            ->seeJsonStructure(['result' => [
                ['name', 'code', 'description', 'retail_price', 'purchase_price']
            ]]);
    }

    public function testGetOneRequest()
    {
        $product = factory(App\Product::class)->create();
        $this->json('GET', "/api/v1/products/{$product->getKey()}")
            ->seeStatusCode(200)
            ->seeJson(['name' => $product->getAttribute('name')])
            ->seeJson(['code' => $product->getAttribute('code')])
            ->seeJson(['description' => $product->getAttribute('description')])
            ->seeJson(['retail_price' => $product->getAttribute('retail_price')])
            ->seeJson(['purchase_price' => $product->getAttribute('purchase_price')]);
    }

    public function testPostRequest()
    {
        
    }

    public function testPutRequest()
    {

    }

    public function testDeleteRequest()
    {

    }

}
