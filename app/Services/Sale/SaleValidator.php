<?php

namespace App\Services\Sale;

use App\MerchandiseSale;
use App\Sale;
use App\Services\Deliverable\DeliverableValidator;
use Illuminate\Http\Request;

class SaleValidator extends DeliverableValidator
{

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Sale::CODE => 'required|unique:sales']);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Sale::CODE => 'required|exists:sales']);
    }

    protected function getValidationRulesForMerchandises(Request $request)
    {
        $rules = [];
        if ($this->hasMerchandises($request)) {
            $rules = array_merge($rules, $this->getValidationRulesForMerchandiseClient($request), [
                $this->getMerchandiseProperty(static::$requestAttributeId) => 'required|exists:merchandises,id',
                $this->getMerchandiseProperty(MerchandiseSale::QUANTITY) => 'required|min:1',
                $this->getMerchandiseProperty(MerchandiseSale::RETAIL_PRICE) => 'required|min:0',
            ]);
        }
        return $rules;
    }
    
}
