<?php

namespace App\Services\Product;

use App\Merchandise;
use App\Product;
use App\Services\AbstractRequestReader;
use Illuminate\Http\Request;

class ProductRequestReader extends AbstractRequestReader
{

    use ProductAttributeBuilder;

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Product::class:
                return $this->readProductAttributes($request);
            case Merchandise::class:
                return $this->readMerchandiseAttributes($request);
            default:
                return [];
        }
    }

    private function readProductAttributes(Request $request)
    {
        return [
            Product::NAME => $request->get(Product::NAME),
            Product::CODE => $request->get(Product::CODE),
            Product::DESCRIPTION => $request->get(Product::DESCRIPTION),
        ];
    }

    private function readMerchandiseAttributes(Request $request)
    {
        return [
            Merchandise::RETAIL_PRICE => $request->input($this->getMerchandiseProperty(Merchandise::RETAIL_PRICE)),
            Merchandise::PURCHASE_PRICE => $request->input($this->getMerchandiseProperty(Merchandise::PURCHASE_PRICE)),
        ];
    }

}
