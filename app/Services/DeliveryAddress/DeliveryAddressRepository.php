<?php

namespace App\Services\DeliveryAddress;

use App\Contact;
use App\DeliveryAddress;
use App\Services\Contactable\ContactableRepository;
use Illuminate\Http\Request;

/**
 * @method DeliveryAddressRequestReader getRequestReader
 */
class DeliveryAddressRepository extends ContactableRepository
{
    
    public function __construct(DeliveryAddressRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $deliveryAddress = new DeliveryAddress($this->readDeliveryAddress($request));
        if ($deliveryAddress->isDefault()) {
            DeliveryAddress::where(DeliveryAddress::IS_DEFAULT, '=', true)->update([DeliveryAddress::IS_DEFAULT => false]);
        }
        if ($deliveryAddress->save() && $this->hasContacts($request)) {
            $deliveryAddress->createContacts($this->readContactCollection($request));
        }
        return $deliveryAddress;
    }

    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        $contactCollection = $this->readContactCollection($request);
        $deliveryAddress->updateContact(array_pop($contactCollection));
    }

    private function readDeliveryAddress(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, DeliveryAddress::class);
    }
    
    private function readContactCollection(Request $request)
    {
        return $this->getRequestReader()->readCollection($request, Contact::class);
    }

}
