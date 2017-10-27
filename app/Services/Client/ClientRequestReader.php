<?php

namespace App\Services\Client;

use App\Client;
use App\Contact;
use App\Services\Contactable\ContactableRequestReader;
use Illuminate\Http\Request;

class ClientRequestReader extends ContactableRequestReader
{
    
    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Client::class:
                return $this->readClientAttributes($request);
            case Contact::class:
                return $this->readContactAttributes($request);
            default:
                return [];
        }
    }

    private function readClientAttributes(Request $request)
    {
        return [
            Client::NAME => $request->get(Client::NAME),
            Client::LEGAL_DOCUMENT_CODE => $request->get(Client::LEGAL_DOCUMENT_CODE),
        ];
    }

}
