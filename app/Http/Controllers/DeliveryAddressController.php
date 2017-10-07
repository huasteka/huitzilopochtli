<?php
namespace App\Http\Controllers;

use App\Contact;
use App\DeliveryAddress;
use App\Schemas\ContactSchema;
use App\Schemas\DeliveryAddressSchema;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class DeliveryAddressController extends ContactableController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::all()));
    }

    public function store(Request $request)
    {
        $deliveryAddress = DeliveryAddress::create($this->parseRequest($request));
        if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
            $deliveryAddress->contacts = $deliveryAddress->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJsonApi($this->getEncoder()->encodeData($deliveryAddress), Response::HTTP_CREATED);
    }

    public function show($deliveryAddressId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(DeliveryAddress::find($deliveryAddressId)));
    }

    public function update(Request $request, $deliveryAddressId)
    {
        return $this->findDeliveryAddressAndExecuteCallback($deliveryAddressId, function (DeliveryAddress $deliveryAddress) use ($request) {
            if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
                $contactArray = $this->createContactsFromRequest($request, true);
                $contact = $deliveryAddress->contacts()->first();
                $contact->fill(array_pop($contactArray));
                $contact->save();
            }
            $deliveryAddress->fill($this->parseRequest($request));
            $deliveryAddress->save();
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

    protected function parseRequest(Request $request)
    {
        return DeliveryAddress::readAttributes($request);
    }
    
    private function getEncoder()
    {
        return $this->createEncoder([
            DeliveryAddress::class => DeliveryAddressSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }
    
}
