<?php
namespace App\Http\Controllers;

use App\Contact;
use App\DeliveryAddress;
use App\Schemas\ContactSchema;
use App\Schemas\DeliveryAddressSchema;
use App\Services\DeliveryAddress\DeliveryAddressService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeliveryAddressController extends ContactableController
{
    
    private $deliveryAddressService;

    public function __construct(DeliveryAddressService $deliveryAddressService)
    {
        $this->deliveryAddressService = $deliveryAddressService;
    }

    /**
     * @api {get} /delivery_addresses Fetch a list of delivery addresses
     * @apiVersion 1.0.0
     * @apiGroup DeliveryAddress
     * @apiName GetDeliveryAddressList
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponseDeliveryAddressJson
     */
    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::all()));
    }

    /**
     * @api {post} /delivery_addresses Create a delivery address
     * @apiVersion 1.0.0
     * @apiGroup DeliveryAddress
     * @apiName CreateDeliveryAddress
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestDeliveryAddressJson
     * @apiUse ResponseDeliveryAddressJson
     * @apiUse ResponseErrorJson
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->getDeliveryAddressService()->validateOnCreate($request));
        $deliveryAddress = $this->getDeliveryAddressService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($deliveryAddress), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /delivery_addresses/:deliveryAddressId Fetch a single delivery address
     * @apiVersion 1.0.0
     * @apiGroup DeliveryAddress
     * @apiName GetDeliveryAddress
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} deliveryAddressId
     * @apiSuccess {Object} data
     * @apiUse ResponseDeliveryAddressJson
     */
    public function show($deliveryAddressId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::find($deliveryAddressId)));
    }

    /**
     * @api {put} /delivery_addresses/:deliveryAddressId Update an existing delivery address
     * @apiVersion 1.0.0
     * @apiGroup DeliveryAddress
     * @apiName UpdateDeliveryAddress
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} deliveryAddressId
     * @apiUse RequestDeliveryAddressJson
     * @apiUse ResponseErrorJson
     */
    public function update(Request $request, $deliveryAddressId)
    {
        return $this->findDeliveryAddressAndExecuteCallback($deliveryAddressId, function (DeliveryAddress $deliveryAddress) use ($request) {
            $this->validateRequest($request, $this->getDeliveryAddressService()->validateOnUpdate($request));
            $this->getDeliveryAddressService()->update($request, $deliveryAddress);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /delivery_addresses/:deliveryAddressId Delete an existing delivery address
     * @apiVersion 1.0.0
     * @apiGroup DeliveryAddress
     * @apiName DeleteDeliveryAddress
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} deliveryAddressId
     * @apiUse ResponseErrorJson
     */
    public function destroy($deliveryAddressId)
    {
        return $this->findDeliveryAddressAndExecuteCallback($deliveryAddressId, function (DeliveryAddress $deliveryAddress) {
            return DB::transaction(function () use ($deliveryAddress) {
                $deliveryAddress->delete();
                return $this->withStatus(Response::HTTP_NO_CONTENT);
            });
        });
    }

    private function findDeliveryAddressAndExecuteCallback($deliveryAddressId, callable $callback)
    {
        $deliveryAddress = DeliveryAddress::find($deliveryAddressId);
        if (is_null($deliveryAddress)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($deliveryAddress);
    }
    
    /**
     * @apiDefine RequestDeliveryAddressJson
     * @apiBody {Boolean} is_default
     * @apiBody {String} phone
     * @apiBody {String} address
     * @apiBody {String} address_complement
     * @apiBody {String} postal_code
     * @apiBody {String} city
     * @apiBody {String} region
     * @apiBody {String} country
     */
    private function getEncoder()
    {
        return $this->createEncoder([
            DeliveryAddress::class => DeliveryAddressSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

    public function getDeliveryAddressService()
    {
        return $this->deliveryAddressService;
    }
    
}
