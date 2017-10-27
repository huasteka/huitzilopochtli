<?php

namespace App\Services\Merchandise;

use App\Merchandise;
use App\Product;
use App\Services\AbstractRepository;
use Illuminate\Http\Request;

/**
 * @method MerchandiseRequestReader getRequestReader
 */
class MerchandiseRepository extends AbstractRepository
{
    
    use MerchandiseRequestChecker;

    public function __construct(MerchandiseRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $product = null;
        if ($this->hasProductId($request)) {
            $product = Product::find($request->get(static::$requestAttributeProductId));
        } else if ($this->hasProduct($request)) {
            $product = new Product($this->readProduct($request));
            $product->save();
        }
        if (!is_null($product)) {
            return $product->createMerchandise($this->readMerchandise($request));
        }
        return null;
    }

    public function update(Request $request, Merchandise $merchandise)
    {
        $merchandise->update($this->readMerchandise($request));
    }

    private function readMerchandise(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Merchandise::class);
    }

    private function readProduct(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Product::class);
    }

}
