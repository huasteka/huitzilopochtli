<?php
namespace App\Http\Controllers;

use App\Merchandise;
use App\Product;
use App\Schemas\MerchandiseSchema;
use App\Schemas\ProductSchema;
use Illuminate\Http\Request;

class MerchandiseController extends StandardController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::all()));
    }

    public function store(Request $request)
    {

    }

    public function show($purchaseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::find($purchaseId)));
    }

    public function update(Request $request, $purchaseId)
    {

    }

    public function destroy($purchaseId)
    {

    }

    protected function parseRequest(Request $request)
    {
        return Merchandise::readAttributes($request);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Merchandise::class => MerchandiseSchema::class,
            Product::class => ProductSchema::class,
        ]);
    }

}