<?php

namespace App\Services\DeliveryAddress;

use App\DeliveryAddress;
use App\Services\Contactable\ContactableRequestReader;
use Illuminate\Http\Request;

class DeliveryAddressRequestReader extends ContactableRequestReader
{

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case DeliveryAddress::class:
            default:
                return $this->readAttributesForDeliveryAddress($request);
        }
    }

    protected function readAttributesForDeliveryAddress(Request $request)
    {
        return [DeliveryAddress::IS_DEFAULT => $request->get(DeliveryAddress::IS_DEFAULT, false)];
    }

}
