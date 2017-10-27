<?php

namespace App\Services\Supplier;

use App\Contact;
use App\Services\Contactable\ContactableRepository;
use App\Supplier;
use Illuminate\Http\Request;

/**
 * @method SupplierRequestReader getRequestReader
 */
class SupplierRepository extends ContactableRepository
{

    public function __construct(SupplierRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $supplier = new Supplier($this->getRequestReader()->readAttributes($request, Supplier::class));
        if ($supplier->save() && $this->hasContacts($request)) {
            $supplier->createContacts($this->getRequestReader()->readCollection($request, Contact::class));
        }
        return $supplier;
    }

    public function update(Request $request, Supplier $supplier)
    {
        $supplier->update($this->getRequestReader()->readAttributes($request, Supplier::class));
    }

}
