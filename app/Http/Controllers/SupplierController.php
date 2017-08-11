<?php
namespace App\Http\Controllers;

use App\Contact;
use App\Services\JsonResponseFormatter;
use App\Supplier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SupplierController extends Controller
{

    public function create(Request $request)
    {
        $this->validateRequest($request, ['legal_document_code' => 'required|unique:suppliers']);
        $supplier = Supplier::create($this->parseRequest($request));
        if ($request->has('contacts')) {
            $supplier->contacts = $supplier->contacts()->saveMany($this->createContactsFromRequest($request));
        }
        return $this->withJson(new JsonResponseFormatter($supplier), Response::HTTP_CREATED);
    }

    private function createContactsFromRequest(Request $request)
    {
        $this->validateContactsRequest($request);
        $contactList = [];
        foreach ($request->get('contacts') as $contactRequest) {
            $contactList[] = $this->createContact($contactRequest);
        }
        return $contactList;
    }

    private function createContact($requestParams)
    {
        return (new Contact())->fill(Contact::readAttributes($requestParams));
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

    private function validateContactsRequest(Request $request)
    {
        $this->validate($request, [
            'contacts.*.phone' => 'required',
            'contacts.*.address' => 'required',
            'contacts.*.address_complement' => 'required',
            'contacts.*.postal_code' => 'required',
            'contacts.*.city' => 'required',
            'contacts.*.region' => 'required',
            'contacts.*.country' => 'required',
        ]);
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
