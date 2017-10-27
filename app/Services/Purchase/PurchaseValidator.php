<?php

namespace App\Services\Purchase;

use App\Contact;
use App\Delivery;
use App\DeliveryAddress;
use App\MerchandisePurchase;
use App\Purchase;
use App\Services\ValidatorInterface;
use Illuminate\Http\Request;

class PurchaseValidator implements ValidatorInterface
{

    use PurchaseRequestChecker;

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Purchase::CODE => 'required|unique:purchases']);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Purchase::CODE => 'required|exists:purchases']);
    }

    private function getValidationRules(Request $request)
    {
        $purchaseRules = [
            Purchase::GROSS_VALUE => 'sometimes|required|min:0',
            Purchase::NET_VALUE => 'sometimes|required|min:0',
            Purchase::DISCOUNT => 'sometimes|required|min:0',
            static::$requestAttributeMerchandises => 'required',
        ];
        return array_merge(
            $purchaseRules,
            $this->getValidationRulesForMerchandises($request),
            $this->getValidationRulesForDelivery($request)
        );
    }

    private function getValidationRulesForMerchandises(Request $request)
    {
        $rules = [];
        if ($this->hasMerchandises($request)) {
            $rules = array_merge($rules, [
                $this->getMerchandiseProperty(static::$requestAttributeId) => 'required|exists:merchandises,id',
                $this->getMerchandiseProperty(MerchandisePurchase::QUANTITY) => 'required|min:1',
                $this->getMerchandiseProperty(MerchandisePurchase::PURCHASE_PRICE) => 'required|min:0',
            ]);
        }
        return $rules;
    }

    private function getValidationRulesForDelivery(Request $request)
    {
        $rules = [];
        if ($this->hasDelivery($request)) {
            $rules = [
                $this->getDeliveryProperty(Delivery::SENT_AT) => 'sometimes|required|date',
                $this->getDeliveryProperty(Delivery::DELIVERY_TIME) => 'sometimes|required|min:1',
                $this->getDeliveryProperty(Delivery::ARRIVED_AT) => 'sometimes|required|date|after:sent_at',
            ];
            if ($this->hasDeliveryAddressId($request)) {
                $rules = array_merge($rules, [
                    $this->getDeliveryProperty(static::$requestAttributeDeliveryAddressId) => 'sometimes|required|exists:delivery_addresses,id'
                ]);
            } else if ($this->hasDeliveryAddress($request)) {
                $rules = array_merge($rules, [
                    $this->getDeliveryAddressProperty(DeliveryAddress::IS_DEFAULT) => 'required|boolean',
                    $this->getContactProperty(Contact::PHONE) => 'required',
                    $this->getContactProperty(Contact::ADDRESS) => 'required',
                    $this->getContactProperty(Contact::ADDRESS_COMPLEMENT) => 'required',
                    $this->getContactProperty(Contact::POSTAL_CODE) => 'required',
                    $this->getContactProperty(Contact::CITY) => 'required',
                    $this->getContactProperty(Contact::REGION) => 'required',
                    $this->getContactProperty(Contact::COUNTRY) => 'required',
                ]);
            }
        }
        return $rules;
    }

}
