<?php

namespace App\Services\Supplier;

use App\Services\Contactable\ContactableService;
use App\Supplier;
use Illuminate\Http\Request;

/**
 * @method SupplierValidator getValidator
 * @method SupplierRepository getRepository
 */
final class SupplierService extends ContactableService
{

    public function __construct(SupplierValidator $validator, SupplierRepository $repository)
    {
        parent::__construct($validator, $repository);
    }

    /**
     * @param Request $request
     * @return Supplier
     */
    public function create(Request $request)
    {
        return $this->getRepository()->create($request);
    }

    /**
     * @param Request $request
     * @param Supplier $supplier
     */
    public function update(Request $request, $supplier)
    {
        $this->getRepository()->update($request, $supplier);
    }

}
