<?php
namespace App\Services;

use App\Merchandise;
use App\Product;
use Illuminate\Http\Request;

class MerchandiseService extends AbstractService
{
    
    public function store(Request $request)
    {
        $product = null;
        if ($request->has('product_id')) {
            $product = Product::find($request->get('product_id'));
        } else if ($request->has('product')) {
            $product = new Product($this->readAttributesForProduct($request));
            $product->save();
        }
        if (!is_null($product)) {
            return $product->createMerchandise($this->readAttributesForMerchandise($request));
        }
        return null;
    }
    
    public function update(Request $request, Merchandise $merchandise)
    {
        $merchandise->update($this->readAttributesForMerchandise($request));
    }

    public function readAttributesForMerchandise(Request $request)
    {
        return [
            Merchandise::RETAIL_PRICE => $request->get(Merchandise::RETAIL_PRICE),
            Merchandise::PURCHASE_PRICE => $request->get(Merchandise::PURCHASE_PRICE, 0.00),
        ];
    }
    
    private function readAttributesForProduct(Request $request)
    {
        return [
            Product::NAME => $request->input($this->property('product', Product::NAME)),
            Product::CODE => $request->input($this->property('product', Product::CODE)),
            Product::DESCRIPTION => $request->input($this->property('product', Product::DESCRIPTION)),
        ];
    }

    public function getValidationRulesOnCreate(Request $request)
    {
        $rules = $this->getValidationRules($request);
        if ($request->has('product')) {
            $rules = array_merge($rules, [$this->property('product', Product::CODE) => 'required|unique:products,code']);
        }
        return $rules;
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return $this->getValidationRules($request);
    }

    private function getValidationRules(Request $request)
    {
        $rules = [];
        if ($request->has('product_id')) {
            $rules = ['product_id' => 'required|exists:products,id'];
        } else if ($request->has('product')) {
            $rules = [$this->property('product', Product::NAME) => 'required'];
        }
        return array_merge($rules, [
            Merchandise::RETAIL_PRICE => 'required|min:0',
            Merchandise::PURCHASE_PRICE => 'sometimes|required|min:0'
        ]);
    }

}