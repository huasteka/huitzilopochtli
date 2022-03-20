<?php

namespace App\Services\Purchase;

use App\MerchandisePurchase;
use App\Purchase;
use App\Services\Deliverable\DeliverableValidator;
use Illuminate\Http\Request;

class PurchaseValidator extends DeliverableValidator
{

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Purchase::CODE => 'required|unique:purchases',
            static::$requestAttributeMerchandises => 'required',
        ]);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Purchase::CODE => 'required|exists:purchases',
        ]);
    }

    protected function getValidationRulesForMerchandises(Request $request)
    {
        $rules = [];
        if ($this->hasMerchandises($request)) {
            $rules = array_merge($rules, $this->getValidationRulesForMerchandiseSupplier($request), [
                $this->getMerchandiseProperty(static::$requestAttributeId) => 'required|exists:merchandises,id',
                $this->getMerchandiseProperty(MerchandisePurchase::QUANTITY) => 'required|min:1',
                $this->getMerchandiseProperty(MerchandisePurchase::PURCHASE_PRICE) => 'required|min:0',
            ]);
        }
        return $rules;
    }

}
