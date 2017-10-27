<?php

namespace App\Services\Product;

use App\Merchandise;
use App\Product;
use App\Services\AbstractRepository;
use Illuminate\Http\Request;

/**
 * @method ProductRequestReader getRequestReader
 */
class ProductRepository extends AbstractRepository
{
    
    use ProductRequestChecker;
    
    public function __construct(ProductRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $product = new Product($this->readProduct($request));
        if ($product->save() && $this->hasMerchandise($request)) {
            $product->createMerchandise($this->readMerchandise($request));
        }
        return $product;
    }

    public function update(Request $request, Product $product)
    {
        $product->fill($this->readProduct($request));
        if ($product->save() && $this->hasMerchandise($request)) {
            $product->updateMerchandise($this->readMerchandise($request));
        }
        return $product;
    }
    
    private function readProduct(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Product::class);
    }
    
    private function readMerchandise(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Merchandise::class);
    }

}
