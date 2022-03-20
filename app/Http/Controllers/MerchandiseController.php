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
     * @api {get} /merchandises Fetch a list of merchandises
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName GetMerchandises
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponseMerchandiseJson
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
     * @apiUse RequestMerchandiseJson
     * @apiBody {Object} [product]
     * @apiBody {Object} [product.name]
     * @apiBody {Object} [product.code]
     * @apiBody {Object} [product.description]
     * @apiUse ResponseMerchandiseJson
     * @apiUse ResponseErrorJson
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getMerchandiseService()->validateOnCreate($request));
        $merchandise = $this->getMerchandiseService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($merchandise), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /merchandises/:merchandiseId Fetch a single merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName GetMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} merchandiseId
     * @apiSuccess {Object} data
     * @apiUse ResponseMerchandiseJson
     */
    public function show($merchandiseId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Merchandise::find($merchandiseId)));
    }

    /**
     * @api {put} /merchandises/:merchandiseId Update an existent merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName UpdateMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} merchandiseId
     * @apiUse RequestMerchandiseJson
     * @apiUse ResponseErrorJson
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
     * @api {delete} /merchandises/:merchandiseId Delete an existent merchandise
     * @apiVersion 1.0.0
     * @apiGroup Merchandise
     * @apiName DeleteMerchandise
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} merchandiseId
     * @apiUse ResponseErrorJson
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

    /**
     * @apiDefine RequestMerchandiseJson
     * @apiBody {Number} product_id
     * @apiBody {Number} retail_price
     * @apiBody {Number} purchase_price
     */
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