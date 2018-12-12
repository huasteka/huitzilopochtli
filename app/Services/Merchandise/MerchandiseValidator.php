<?php

namespace App\Services\Merchandise;

use App\Merchandise;
use App\Product;
use App\Services\ValidatorInterface;
use Illuminate\Http\Request;

class MerchandiseValidator implements ValidatorInterface
{

    use MerchandiseRequestChecker;

    public function getValidationRulesOnCreate(Request $request)
    {
        return $this->getValidationRules($request, [
            $this->getProductProperty(Product::CODE) => 'required|exists:products,code'
        ]);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return $this->getValidationRules($request, [
            $this->getProductProperty(Product::CODE) => 'required'
        ]);
    }

    private function getValidationRules(Request $request, array $productRules = [])
    {
        $rules = [];
        if ($this->hasProductId($request)) {
            $rules = [static::$requestAttributeProductId => 'required|exists:products,id'];
        } else if ($this->hasProduct($request)) {
            $rules = array_merge([$this->getProductProperty(Product::NAME) => 'required'], $productRules);
        }
        return array_merge($rules, [
            Merchandise::RETAIL_PRICE => 'required|min:0',
            Merchandise::PURCHASE_PRICE => 'sometimes|required|min:0',
        ]);
    }

}
