<?php
namespace App\Services;

use App\Merchandise;
use App\Product;
use Illuminate\Http\Request;

final class ProductService extends AbstractService
{

    public function store(Request $request)
    {
        $product = new Product($this->readAttributesForProduct($request));
        if ($product->save() && $request->has('merchandise')) {
            $product->createMerchandise($this->readAttributesForMerchandise($request));
        }
        return $product;
    }
    
    public function update(Request $request, Product $product)
    {
        $product->fill($this->readAttributesForProduct($request));
        if ($product->save() && $request->has('merchandise')) {
            $product->updateMerchandise($this->readAttributesForMerchandise($request));
        }
        return $product;
    }

    public function readAttributesForProduct(Request $request)
    {
        return [
            Product::NAME => $request->get(Product::NAME),
            Product::CODE => $request->get(Product::CODE),
            Product::DESCRIPTION => $request->get(Product::DESCRIPTION),
        ];
    }

    public function readAttributesForMerchandise(Request $request)
    {
        if ($request->has('merchandise')) {
            return [
                Merchandise::RETAIL_PRICE => $request->input($this->property('merchandise', Merchandise::RETAIL_PRICE)),
                Merchandise::PURCHASE_PRICE => $request->input($this->property('merchandise', Merchandise::PURCHASE_PRICE)),
            ];
        }
        return [];
    }

    public function getValidationRulesOnCreate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Product::CODE => 'required|unique:products']);
    }

    public function getValidationRulesOnUpdate(Request $request)
    {
        return array_merge($this->getValidationRules($request), [Product::CODE => 'required|exists:products']);
    }

    protected function getValidationRules(Request $request)
    {
        $rules = [Product::NAME => 'required|min:3'];
        if ($request->has('merchandise')) {
            $rules = array_merge($rules, [
                $this->property('merchandise', Merchandise::RETAIL_PRICE) => 'required|min:0',
                $this->property('merchandise', Merchandise::PURCHASE_PRICE) => 'sometimes|required|min:0',
            ]);
        }
        return $rules;
    }

}