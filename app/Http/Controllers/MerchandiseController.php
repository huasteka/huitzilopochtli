<?php
namespace App\Http\Controllers;

use App\Merchandise;
use App\Product;
use App\Schemas\MerchandiseSchema;
use App\Schemas\ProductSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class MerchandiseController extends StandardController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::all()));
    }

    public function store(Request $request)
    {
        $this->validate($request, Merchandise::validationRulesOnCreateAndUpdate());
        $product = $this->findOrCreateProduct($request);
        $merchandise = new Merchandise($this->parseRequest($request));
        $merchandise->setActive();
        DB::table((new Merchandise())->getTable())->update([Merchandise::IS_ACTIVE => false]);

        $result = $product->merchandises()->save($merchandise);
        return $this->withJsonApi($this->getEncoder()->encodeData($result), Response::HTTP_CREATED);
    }
    
    private function findOrCreateProduct(Request $request)
    {
        $product = null;
        if ($request->has('product_id')) {
            $this->validate($request, Merchandise::validationRulesOnCreateProduct());
            $product = Product::find($request->get('product_id'));
        } else if ($request->has(Merchandise::RELATIONSHIP_PRODUCT)) {
            $productArray = $request->get(Merchandise::RELATIONSHIP_PRODUCT);
            $productValidator = $this->getValidationFactory()->make($productArray, Product::validationRulesOnCreate());
            if ($productValidator->fails()) {
                $this->throwValidationException($request, $productValidator);
            } else {
                $product = Product::create($productArray);
            }
        }
        return $product;
    }

    public function show($merchandiseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::find($merchandiseId)));
    }

    public function update(Request $request, $merchandiseId)
    {
        return $this->findMerchandiseAndExecuteCallback($merchandiseId, function (Merchandise $merchandise) use ($request) {
            $this->validate($request, Merchandise::validationRulesOnCreateAndUpdate());
            $merchandise->fill($this->parseRequest($request));
            $merchandise->save();
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