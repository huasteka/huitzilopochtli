<?php
namespace App\Http\Controllers;

use App\Merchandise;
use App\Purchase;
use App\Schemas\MerchandiseSchema;
use App\Schemas\PurchaseSchema;
use Illuminate\Http\Request;

class PurchaseController extends RestController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::all()));
    }
    
    public function store(Request $request)
    {
        
    }

    public function show($purchaseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Purchase::find($purchaseId)));
    }

    public function update(Request $request, $purchaseId)
    {
        
    }

    public function destroy($purchaseId)
    {
        
    }
    
    protected function parseRequest(Request $request)
    {
        return Purchase::readAttributes($request);
    }
    
    private function getEncoder()
    {
        return $this->createEncoder([
            Purchase::class => PurchaseSchema::class,
            Merchandise::class => MerchandiseSchema::class,
        ]);
    }

}
