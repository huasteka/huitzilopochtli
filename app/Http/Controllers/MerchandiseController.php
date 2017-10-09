<?php
namespace App\Http\Controllers;

use App\Merchandise;
use App\Product;
use App\Schemas\MerchandiseSchema;
use App\Schemas\ProductSchema;
use App\Services\MerchandiseService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MerchandiseController extends RestController
{
    
    private $merchandiseService;

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::all()));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->getMerchandiseService()->getValidationRulesOnCreate($request));
        $merchandise = $this->getMerchandiseService()->store($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($merchandise), Response::HTTP_CREATED);
    }

    public function show($merchandiseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::find($merchandiseId)));
    }

    public function update(Request $request, $merchandiseId)
    {
        return $this->findMerchandiseAndExecuteCallback($merchandiseId, function (Merchandise $merchandise) use ($request) {
            $this->validate($request, $this->getMerchandiseService()->getValidationRulesOnUpdate($request));
            $this->getMerchandiseService()->update($request, $merchandise);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function destroy($merchandiseId)
    {
        return $this->findMerchandiseAndExecuteCallback($merchandiseId, function (Merchandise $merchandise) {
            $merchandise->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    private function findMerchandiseAndExecuteCallback($merchandiseId, callable $callback)
    {
        $merchandise = Merchandise::find($merchandiseId);
        if (is_null($merchandise)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($merchandise);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Merchandise::class => MerchandiseSchema::class,
            Product::class => ProductSchema::class,
        ]);
    }

    private function getMerchandiseService()
    {
        if (is_null($this->merchandiseService)) {
            $this->merchandiseService = new MerchandiseService();
        }
        return $this->merchandiseService;
    }

}