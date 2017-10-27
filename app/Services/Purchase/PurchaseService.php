<?php

namespace App\Services\Purchase;

use App\Purchase;
use App\Services\AbstractService;
use Illuminate\Http\Request;

/**
 * @method PurchaseValidator getValidator
 * @method PurchaseRepository getRepository
 */
class PurchaseService extends AbstractService
{

    public function __construct(PurchaseValidator $validator, PurchaseRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Purchase
     */
    public function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Purchase $purchase
     * @return void
     */
    public function update(Request $request, $purchase)
    {
        $this->getRepository()->update($request, $purchase);
    }

}
