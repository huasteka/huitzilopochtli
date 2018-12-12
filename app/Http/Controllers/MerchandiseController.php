<?php
namespace App\Http\Controllers;

use App\Merchandise;
use App\Product;
use App\Schemas\MerchandiseSchema;
use App\Schemas\ProductSchema;
use App\Services\Merchandise\MerchandiseService;
use App\Util\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MerchandiseController extends RestController
{
    
    private $merchandiseService;

    public function __construct(MerchandiseService $merchandiseService)
    {
        $this->merchandiseService = $merchandiseService;
    }

    /**
     * @api {get} /merchandises Fetch merchandise list
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName GetMerchandises
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::paginate($pageSize)));
    }

    /**
     * @api {post} /merchandises Create merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName CreateMerchandise
     * @apiHeader {String} Authorization Generated JWT token
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getMerchandiseService()->validateOnCreate($request));
        $merchandise = $this->getMerchandiseService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($merchandise), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /merchandises/:merchandiseId Fetch merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName GetMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function show($merchandiseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::find($merchandiseId)));
    }

    /**
     * @api {put} /merchandises/:merchandiseId Update merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName UpdateMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     */
    public function update(Request $request, $merchandiseId)
    {
        return $this->findMerchandiseAndExecuteCallback($merchandiseId, function (Merchandise $merchandise) use ($request) {
            $this->validate($request, $this->getMerchandiseService()->validateOnUpdate($request));
            $this->getMerchandiseService()->update($request, $merchandise);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /merchandises/:merchandiseId Delete merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName DeleteMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     */
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
        return $this->merchandiseService;
    }

}