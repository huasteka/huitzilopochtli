<?php

namespace App\Services\Supplier;

use App\Services\Contactable\ContactableValidator;
use App\Supplier;
use Illuminate\Http\Request;

class SupplierValidator extends ContactableValidator
{

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Supplier::LEGAL_DOCUMENT_CODE => 'required|unique:suppliers',
        ]);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [
            Supplier::LEGAL_DOCUMENT_CODE => 'required|exists:suppliers',
        ]);
    }

    private function getValidationRules(Request $request)
    {
        $rules = [
            Supplier::NAME => 'required',
            Supplier::TRADE_NAME => 'required',
        ];
        return array_merge($rules, $this->getValidationRulesForContacts($request));
    }
    
}
