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
        if ($deliveryAddress->save()) {
            $deliveryAddress->createContactByAttributes($this->readContactAttributes($request));
        }
        return $deliveryAddress;
    }

    public function update(Request $request, DeliveryAddress $deliveryAddress)
    {
        $attributes = $this->readDeliveryAddress($request);
        $updatedDeliveryAddress = new DeliveryAddress($attributes);
        if ($updatedDeliveryAddress->isDefault()) {
            DeliveryAddress::where(DeliveryAddress::IS_DEFAULT, '=', true)->update([DeliveryAddress::IS_DEFAULT => false]);
        }

        $deliveryAddress->update($attributes);

        $contactAttributes = $this->readContactAttributes($request);
        $contact = new Contact($contactAttributes);
        $deliveryAddress->updateContact($contact);
    }

    private function readDeliveryAddress(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, DeliveryAddress::class);
    }
    
    private function readContactAttributes(Request $request)
    {
        return $this->getRequestReader()->readAttributes($request, Contact::class);
    }

}
