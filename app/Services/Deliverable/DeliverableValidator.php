<?php

namespace App\Services\Deliverable;

use App\Contact;
use App\Deliverable;
use App\Delivery;
use App\DeliveryAddress;
use App\MerchandisePurchase;
use App\MerchandiseSale;
use App\Services\ValidatorInterface;
use Illuminate\Http\Request;

abstract class DeliverableValidator implements ValidatorInterface
{

    use DeliverableRequestChecker;

    protected function getValidationRules(Request $request)
    {
        $deliverableRules = [
            Deliverable::GROSS_VALUE => 'sometimes|required|min:0',
            Deliverable::NET_VALUE => 'sometimes|required|min:0',
            Deliverable::DISCOUNT => 'sometimes|required|min:0',
            static::$requestAttributeMerchandises => 'required',
        ];
        return array_merge(
            $deliverableRules,
            $this->getValidationRulesForMerchandises($request),
            $this->getValidationRulesForDelivery($request)
        );
    }

    abstract protected function getValidationRulesForMerchandises(Request $request);

    protected function getValidationRulesForDelivery(Request $request)
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
    
    protected function getValidationRulesForMerchandiseSupplier(Request $request)
    {
        return $this->getValidationRulesForMerchandiseDependency($request, MerchandisePurchase::SUPPLIER_ID);
    }

    protected function getValidationRulesForMerchandiseClient(Request $request)
    {
        return $this->getValidationRulesForMerchandiseDependency($request, MerchandiseSale::CLIENT_ID);
    }

    private function getValidationRulesForMerchandiseDependency(Request $request, $dependencyProperty)
    {
        $rules = [];
        $merchandises = $request->get(static::$requestAttributeMerchandises);
        for ($c = 0; $c < count($merchandises); $c++) {
            if ($this->hasSupplierAt($request, $c)) {
                $key = $this->getMerchandisePropertyAt($dependencyProperty, $c);
                $rules[$key] = 'required|exists:suppliers,id';
            }
        }
        return $rules;
    }

}
