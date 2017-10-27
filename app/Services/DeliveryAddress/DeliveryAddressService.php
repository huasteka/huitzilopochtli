<?php

namespace App\Services\DeliveryAddress;

use App\DeliveryAddress;
use App\Services\Contactable\ContactableService;
use Illuminate\Http\Request;

/**
 * @method DeliveryAddressValidator getValidator
 * @method DeliveryAddressRepository getRepository
 */
final class DeliveryAddressService extends ContactableService
{
    
    public function __construct(DeliveryAddressValidator $validator, DeliveryAddressRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return DeliveryAddress
     */
    function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param DeliveryAddress $deliveryAddress
     * @return void
     */
    function update(Request $request, $deliveryAddress)
    {
        $this->getRepository()->update($request, $deliveryAddress);
    }

}
