<?php
namespace App\Http\Controllers;

use App\Contact;
use App\Schemas\ContactSchema;
use App\Schemas\SupplierSchema;
use App\Services\Supplier\SupplierService;
use App\Supplier;
use App\Util\Pagination;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

final class SupplierController extends ContactableController
{

    private $supplierService;
    
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * @api {get} /suppliers Fetch a list of suppliers
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName GetSuppliers
     * @apiHeader {String} Authorization User generated JWT token
     * @apiUse RequestPagination
     * @apiSuccess {Object[]} data
     * @apiUse ResponseSupplierJson
     */
    public function index(Request $request)
    {
        $pageSize = Pagination::getInstance($request)->getPageSize();
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::paginate($pageSize)));
    }

    /**
     * @api {post} /suppliers Create supplier
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName CreateSupplier
     * @apiHeader {String} Authorization Generated JWT token
     * @apiUse RequestSupplierJson
     * @apiUse ResponseSupplierJson
     * @apiUse ResponseErrorJson
     */
    public function store(Request $request)
    {
        $this->validateRequest($request, $this->getSupplierService()->validateOnCreate($request));
        $supplier = $this->getSupplierService()->create($request);
        return $this->withJsonApi($this->getEncoder()->encodeData($supplier), Response::HTTP_CREATED);
    }

    /**
     * @api {get} /suppliers/:supplierId Fetch a single supplier
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName GetSupplier
     * @apiHeader {String} Authorization User generated JWT token
     * @apiSuccess {Object} data
     * @apiUse ResponseSupplierJson
     */
    public function show($supplierId)
    {
        return $this->withJsonApi($this->getEncoder()->encodeData(Supplier::find($supplierId)));
    }

    /**
     * @api {put} /suppliers/:supplierId Update an existing supplier
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName UpdateSupplier
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} supplierId
     * @apiUse RequestSupplierJson
     * @apiUse ResponseErrorJson
     */
    public function update(Request $request, $supplierId)
    {
        return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) use ($request) {
            $legalDocumentCode = Supplier::LEGAL_DOCUMENT_CODE;
            $this->validateRequest($request, $this->getSupplierService()->validateOnUpdate($request), [
                $legalDocumentCode => "required|unique:suppliers,{$legalDocumentCode},{$supplier->id}"
            ]);
            $this->getSupplierService()->update($request, $supplier);
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {delete} /suppliers/:supplierId Delete an existing supplier
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName DeleteSupplier
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} supplierId
     * @apiUse ResponseErrorJson
     */
    public function destroy($supplierId)
    {
        return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) {
            $supplier->delete();
            return $this->withStatus(Response::HTTP_NO_CONTENT);
        });
    }

    /**
     * @api {post} /suppliers/:supplierId/contacts Create supplier's contact
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName CreateSupplierContact
     * @apiHeader {String} Authorization Generated JWT token
     * @apiParam {Number} supplierId
     * @apiUse RequestContactJson
     * @apiUse ResponseErrorJson
     */
    public function storeContact(Request $request, $supplierId)
    {
        return $this->storeContactHandler($request, $this->getSupplierService(), function (callable $createContact) use ($supplierId) {
            return $this->findSupplierAndExecuteCallback($supplierId, function (Supplier $supplier) use ($createContact) {
                return $createContact($supplier);
            });
        });
    }

    /**
     * @api {put} /suppliers/:supplierId/contacts/:contactId Update supplier's contact
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName UpdateSupplierContact
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} supplierId
     * @apiParam {Number} contactId
     * @apiUse RequestContactJson
     * @apiUse ResponseErrorJson
     */
    public function updateContact(Request $request, $supplierId, $contactId)
    {
        return $this->updateContactHandler($request, $this->getSupplierService(), $contactId, function (callable $updateContact) use ($supplierId) {
            return $this->findSupplierAndExecuteCallback($supplierId, function () use ($updateContact) {
                return $updateContact();
            });
        });
    }

    /**
     * @api {delete} /suppliers/:supplierId/contacts/:contactId Delete supplier's contact
     * @apiVersion 1.0.0
     * @apiGroup Supplier
     * @apiName DeleteSupplierContact
     * @apiHeader {String} Authorization User generated JWT token
     * @apiParam {Number} supplierId
     * @apiParam {Number} contactId
     * @apiUse ResponseErrorJson
     */
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

    /**
     * @apiDefine RequestSupplierJson
     * @apiBody {String} name
     * @apiBody {String} trade_name
     * @apiBody {String} legal_document_code
     */
    private function getEncoder()
    {
        $entityMap = [
            Supplier::class => SupplierSchema::class,
            Contact::class => ContactSchema::class,
        ];

        $includedPaths = [
            Supplier::RELATIONSHIP_CONTACTS,
        ];

        return $this->createEncoder($entityMap, $includedPaths);
    }

    private function getSupplierService()
    {
        return $this->supplierService;
    }

}
