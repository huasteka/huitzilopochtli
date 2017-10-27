<?php

namespace App\Services\Merchandise;

use Illuminate\Http\Request;

trait MerchandiseRequestChecker
{

    use MerchandiseAttributeBuilder;

    private function hasProduct(Request $request)
    {
        return $request->has(static::$requestAttributeProduct);
    }

    private function hasProductId(Request $request)
    {
        return $request->has(static::$requestAttributeProductId);
    }

}
