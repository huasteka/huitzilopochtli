<?php

namespace App\Services\Sale;

use App\Sale;
use App\Services\AbstractService;
use Illuminate\Http\Request;

/**
 * @method SaleValidator getValidator
 * @method SaleRepository getRepository
 */
class SaleService extends AbstractService
{

    public function __construct(SaleValidator $validator, SaleRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Sale
     */
    public function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Sale $sale
     * @return void
     */
    public function update(Request $request, $sale)
    {
        $this->getRepository()->update($request, $sale);
    }

}
