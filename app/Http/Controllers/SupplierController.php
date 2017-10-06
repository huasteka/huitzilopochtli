<?php
namespace App\Http\Controllers;

use App\Contact;
use App\Schemas\ContactSchema;
use App\Schemas\SupplierSchema;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierController extends ContactableController
{

    public function index()
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::all()));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request, Supplier::validationRulesOnCreate());
        $supplier = Supplier::create($this->parseRequest($request));
        if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
            $supplier->contacts = $supplier->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJsonApi($this->getEncoder()->encodeData($supplier), Response::HTTP_CREATED);
    }

    public function show($supplierId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::find($supplierId)));
    }

    public function update(Request $request, $supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (is_null($supplier)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        $this->validateRequest($request, Supplier::validationRulesOnUpdate());
        $supplier->fill($this->parseRequest($request));
        $supplier->save();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    public function destroy($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (is_null($supplier)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        }
        $supplier->delete();
        return $this->withStatus(Response::HTTP_NO_CONTENT);
    }

    protected function parseRequest(Request $request)
    {
        return Supplier::readAttributes($request);
    }

    private function getEncoder()
    {
        return $this->createEncoder([
            Supplier::class => SupplierSchema::class,
            Contact::class => ContactSchema::class,
        ]);
    }

}
