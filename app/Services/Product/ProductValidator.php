<?php

namespace App\Services\Product;

use App\Merchandise;
use App\Product;
use App\Services\ValidatorInterface;
use Illuminate\Http\Request;

class ProductValidator implements ValidatorInterface
{

    use ProductRequestChecker;

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Product::CODE => 'required|unique:products']);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Product::CODE => 'required|exists:products']);
    }

    private function getValidationRules(Request $request)
    {
        $rules = [Product::NAME => 'required|min:3'];
        if ($this->hasMerchandise($request)) {
            $rules = array_merge($rules, [
                $this->getMerchandiseProperty(Merchandise::RETAIL_PRICE) => 'required|min:0',
                $this->getMerchandiseProperty(Merchandise::PURCHASE_PRICE) => 'sometimes|required|min:0',
            ]);
        }
        return $rules;
    }

}
