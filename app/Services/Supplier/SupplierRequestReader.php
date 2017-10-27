<?php

namespace App\Services\Supplier;

use App\Contact;
use App\Services\Contactable\ContactableRequestReader;
use App\Supplier;
use Illuminate\Http\Request;

class SupplierRequestReader extends ContactableRequestReader
{
    
    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Supplier::class:
                return $this->readSupplierAttributes($request);
            case Contact::class:
                return $this->readContactAttributes($request);
            default:
                return [];
        }
    }

    private function readSupplierAttributes(Request $request)
    {
        return [
            Supplier::NAME => $request->get(Supplier::NAME),
            Supplier::TRADE_NAME => $request->get(Supplier::TRADE_NAME),
            Supplier::LEGAL_DOCUMENT_CODE => $request->get(Supplier::LEGAL_DOCUMENT_CODE),
        ];
    }

}
