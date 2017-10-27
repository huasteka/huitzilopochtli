<?php

namespace App\Services\Purchase;

use App\Contact;
use App\Delivery;
use App\DeliveryAddress;
use App\Purchase;
use App\Services\AbstractRequestReader;
use Illuminate\Http\Request;

class PurchaseRequestReader extends AbstractRequestReader
{

    use PurchaseAttributeBuilder;

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Purchase::class:
                return $this->readPurchaseAttributes($request);
            case Delivery::class:
                return $this->readDeliveryAttributes($request);
            case DeliveryAddress::class:
                return $this->readDeliveryAddressAttributes($request);
            case Contact::class:
                return $this->readContactAttributes($request);
            default:
                return [];
        }
    }

    private function readPurchaseAttributes(Request $request)
    {
        return [
            Purchase::CODE => $request->get(Purchase::CODE),
            Purchase::DESCRIPTION => $request->get(Purchase::DESCRIPTION),
            Purchase::GROSS_VALUE => $request->get(Purchase::GROSS_VALUE, 0.00),
            Purchase::NET_VALUE => $request->get(Purchase::NET_VALUE),
            Purchase::DISCOUNT => $request->get(Purchase::DISCOUNT, 0.00),
        ];
    }

    private function readDeliveryAttributes(Request $request)
    {
        return [
            Delivery::SENT_AT => $request->input($this->getDeliveryProperty(Delivery::SENT_AT)),
            Delivery::DELIVERY_TIME => $request->input($this->getDeliveryProperty(Delivery::DELIVERY_TIME)),
            Delivery::ARRIVED_AT => $request->input($this->getDeliveryProperty(Delivery::ARRIVED_AT)),
        ];
    }

    private function readDeliveryAddressAttributes(Request $request)
    {
        return [
            DeliveryAddress::IS_DEFAULT => $request->input($this->getDeliveryAddressProperty(DeliveryAddress::IS_DEFAULT))
        ];
    }

    private function readContactAttributes(Request $request)
    {
        $contactList = $request->input($this->getDeliveryAddressProperty(self::$requestAttributeContacts));
        return array_pop($contactList);
    }

}
