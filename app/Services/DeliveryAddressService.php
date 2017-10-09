<?php
namespace App\Services;

use App\DeliveryAddress;
use Illuminate\Http\Request;

class DeliveryAddressService extends ContactableService
{

    public function store(Request $request)
    {
        $deliveryAddress = new DeliveryAddress($this->readAttributesForDeliveryAddress($request));
        if ($deliveryAddress->isDefault()) {
            DeliveryAddress::where(DeliveryAddress::IS_DEFAULT, '=', true)->update([DeliveryAddress::IS_DEFAULT => false]);
        }
        if ($deliveryAddress->save() && $request->has('contacts')) {
            $deliveryAddress->createContacts($this->getContactCollection($request));
        }
        return $deliveryAddress;
    }

    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        $contactCollection = $this->getContactCollection($request);
        $deliveryAddress->updateContact(array_pop($contactCollection));
    }

    public function readAttributesForDeliveryAddress(Request $request)
    {
        return [DeliveryAddress::IS_DEFAULT => $request->get(DeliveryAddress::IS_DEFAULT, false)];
    }
    
    public function getValidationRulesOnCreateAndUpdate(Request $request)
    {
        $rules = [DeliveryAddress::IS_DEFAULT => 'required|boolean'];
        return array_merge($rules, $this->getValidationRulesForContacts($request));
    }

}
