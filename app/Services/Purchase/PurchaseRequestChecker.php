<?php

namespace App\Services\Purchase;

use Illuminate\Http\Request;

trait PurchaseRequestChecker
{

    use PurchaseAttributeBuilder;

    protected function hasMerchandises(Request $request)
    {
        return $request->has(static::$requestAttributeMerchandises);
    }

    protected function hasDelivery(Request $request)
    {
        return $request->has(static::$requestAttributeDelivery);
    }

    protected function hasDeliveryAddress(Request $request)
    {
        return $request->has($this->getDeliveryProperty(static::$requestAttributeDeliveryAddress));
    }

    protected function hasDeliveryAddressId(Request $request)
    {
        return $request->has($this->getDeliveryProperty(static::$requestAttributeDeliveryAddressId));
    }

}
