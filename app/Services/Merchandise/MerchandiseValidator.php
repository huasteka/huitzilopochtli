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
        return $this->getValidationRules($request);
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
        if ($this->hasProduct($request)) {
            $rules = array_merge([
                $this->getProductProperty(Product::NAME) => 'required',
                $this->getProductProperty(Product::CODE) => 'required|unique:products,code'
            ], $productRules);
        } else {
            $rules = [static::$requestAttributeProductId => 'required|exists:products,id'];
        }

        return array_merge($rules, [
            Merchandise::RETAIL_PRICE => 'required|min:0',
            Merchandise::PURCHASE_PRICE => 'sometimes|required|min:0',
        ]);
    }

}
