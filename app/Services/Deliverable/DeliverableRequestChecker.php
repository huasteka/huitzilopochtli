<?php

namespace App\Services\Deliverable;

use Illuminate\Http\Request;

trait DeliverableRequestChecker
{

    use DeliverablePropertyBuilder;

    protected function hasMerchandises(Request $request)
    {
        return $request->has(static::$requestAttributeMerchandises);
    }
    
    protected function hasSupplierAt(Request $request, $indexAt)
    {
        return $request->has($this->getMerchandisePropertyAt(static::$requestAttributeMerchandisesSupplierId, $indexAt));
    }

    protected function hasClientAt(Request $request, $indexAt)
    {
        return $request->has($this->getMerchandisePropertyAt(static::$requestAttributeMerchandisesClientId, $indexAt));
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
