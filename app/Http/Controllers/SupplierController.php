<?php
namespace App\Http\Controllers;

use App\Contact;
use App\Schemas\ContactSchema;
use App\Schemas\SupplierSchema;
use App\Services\Supplier\SupplierService;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class SupplierController extends ContactableController
{

    private $supplierService;
    
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getSupplierService()->validateOnCreate($request));
        $supplier = $this->getSupplierService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($supplier), Response::HTTP_CREATED);
    }

    public function show($supplierId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::find($supplierId)));
    }

    public function update(Request $request, $supplierId)
    {
        return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) use ($request) {
            $this->validateRequest($request, $this->getSupplierService()->validateOnUpdate($request));
            $this->getSupplierService()->update($request, $supplier);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    public function destroy($supplierId)
    {
        return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) {
            $supplier->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }
    
    public function storeContact(Request $request, $supplierId)
    {
        return $this->storeContactHandler($request, $this->getSupplierService(), function (callable $createContact) use ($supplierId) {
            return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) use ($createContact) {
                return $createContact($supplier);
            });
        });
    }

    public function updateContact(Request $request, $supplierId, $contactId)
    {
        return $this->updateContactHandler($request, $this->getSupplierService(), $contactId, function (callable $updateContact) use ($supplierId) {
            return $this->findSupplierAndExecuteCallback($supplierId, function () use ($updateContact) {
                return $updateContact();
            });
        });
    }

    public function destroyContact(Request $request, $supplierId, $contactId)
    {
        return $this->destroyContactHandler($request, $contactId, function (callable $destroyContact) use ($supplierId) {
            return $this->findSupplierAndExecuteCallback($supplierId, function () use ($destroyContact) {
                return $destroyContact();
            });
        });
    }

    private function findSupplierAndExecuteCallback($supplierId, callable $callback)
    {
        $supplier = Supplier::find($supplierId);
        if (is_null($supplier)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        return $callback($supplier);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Supplier::class => SupplierSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

    private function getSupplierService()
    {
        return $this->supplierService;
    }

}
