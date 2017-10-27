<?php

namespace App\Services\Merchandise;

use App\Merchandise;
use App\Product;
use App\Services\AbstractRequestReader;
use Illuminate\Http\Request;

class MerchandiseRequestReader extends AbstractRequestReader
{

    use MerchandiseAttributeBuilder;

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Merchandise::class:
                return $this->readMerchandiseAttributes($request);
            case Product::class:
                return $this->readProductAttributes($request);
            default:
                return [];
        }
    }

    private function readMerchandiseAttributes(Request $request)
    {
        return [
            Merchandise::RETAIL_PRICE => $request->get(Merchandise::RETAIL_PRICE),
            Merchandise::PURCHASE_PRICE => $request->get(Merchandise::PURCHASE_PRICE, 0.00),
        ];
    }

    private function readProductAttributes(Request $request)
    {
        return [
            Product::NAME => $request->input($this->getProductProperty(Product::NAME)),
            Product::CODE => $request->input($this->getProductProperty(Product::CODE)),
            Product::DESCRIPTION => $request->input($this->getProductProperty(Product::DESCRIPTION)),
        ];
    }

}
