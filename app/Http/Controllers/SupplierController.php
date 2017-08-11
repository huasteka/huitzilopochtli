<?php
namespace App\Http\Controllers;

use App\Services\JsonResponseFormatter;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierController extends ContactableController
{

    public function create(Request $request)
    {
        $this->validateRequest($request, ['legal_document_code' => 'required|unique:suppliers']);
        $supplier = Supplier::create($this->parseRequest($request));
        if ($request->has(self::REQUEST_ATTRIBUTE_CONTACTS)) {
            $supplier->contacts = $supplier->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJson(new JsonResponseFormatter($supplier), Response::HTTP_CREATED);
    }

    public function update(Request $request, $supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (is_null($supplier)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        } else {
            $this->validateRequest($request, ['legal_document_code' => 'required']);
            $supplier->fill($this->parseRequest($request));
            $supplier->save();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        }
    }

    private function validateRequest(Request $request, array $newRules = [])
    {
        $defaultRules = [
            'name' => 'required',
            'trade_name' => 'required',
        ];
        $this->validate($request, array_merge($defaultRules, $newRules));
    }

    private function parseRequest(Request $request)
    {
        return Supplier::readAttributes($request);
    }

    public function destroy($supplierId)
    {
        $supplier = Supplier::find($supplierId);
        if (is_null($supplier)) {
            return $this->withStatus(Response::HTTP_NOT_FOUND);
        } else {
            $supplier->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        }
    }

    public function findOne($supplierId)
    {
        return $this->withJson(new JsonResponseFormatter(Supplier::find($supplierId)));
    }

    public function findAll()
    {
        return $this->withJson(new JsonResponseFormatter(Supplier::all()));
    }

}
