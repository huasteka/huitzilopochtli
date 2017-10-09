<?php
namespace App\Services;

use App\Contactable;
use App\Supplier;
use Illuminate\Http\Request;

final class SupplierService extends ContactableService
{

    public function store(Request $request)
    {
        $supplier = new Supplier($this->readAttributesForSupplier($request));
        if ($supplier->save() && $request->has('contacts')) {
            $supplier->createContacts($this->getContactCollection($request));
        }
        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($this->readAttributesForSupplier($request));
    }

    public function readAttributesForSupplier(Request $request)
    {
        return [
            Supplier::NAME => $request->get(Supplier::NAME),
            Supplier::TRADE_NAME => $request->get(Supplier::TRADE_NAME),
            Supplier::LEGAL_DOCUMENT_CODE => $request->get(Supplier::LEGAL_DOCUMENT_CODE),
        ];
    }

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