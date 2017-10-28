<?php

namespace App\Services\Sale;

use App\Sale;
use App\Services\Deliverable\DeliverableRequestReader;
use Illuminate\Http\Request;

class SaleRequestReader extends DeliverableRequestReader
{

    public function readAttributes(Request $request, $type)
    {
        switch ($type) {
            case Sale::class:
                return $this->readDeliverableAttributes($request);
            default:
                return parent::readAttributes($request, $type);
        }
    }

}
