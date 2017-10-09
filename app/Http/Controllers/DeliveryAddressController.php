<?php
namespace App\Http\Controllers;

use App\Contact;
use App\DeliveryAddress;
use App\Schemas\ContactSchema;
use App\Schemas\DeliveryAddressSchema;
use App\Services\DeliveryAddressService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeliveryAddressController extends ContactableController
{
    
    private $deliveryAddressService;

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::all()));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->getDeliveryAddressService()->getValidationRulesOnCreateAndUpdate($request));
        $deliveryAddress = $this->getDeliveryAddressService()->store($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($deliveryAddress), Response::HTTP_CREATED);
    }

    public function show($deliveryAddressId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::find($deliveryAddressId)));
    }

    public function update(Request $request, $deliveryAddressId)
    {
        return $this->findDeliveryAddressAndExecuteCallback($deliveryAddressId, function (DeliveryAddress $deliveryAddress) use ($request) {
            $this->getDeliveryAddressService()->update($request, $deliveryAddress);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

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
    
    private function getEncoder()
    {
        return $this->createEncoder([
            DeliveryAddress::class => DeliveryAddressSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

    public function getDeliveryAddressService()
    {
        if (is_null($this->deliveryAddressService)) {
            $this->deliveryAddressService = new DeliveryAddressService();
        }
        return $this->deliveryAddressService;
    }
    
}
