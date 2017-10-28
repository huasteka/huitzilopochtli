<?php

namespace App\Services\Sale;

use App\MerchandiseSale;
use App\Sale;
use App\Services\Deliverable\DeliverableRepository;
use Illuminate\Http\Request;

/**
 * @method SaleRequestReader getRequestReader
 */
class SaleRepository extends DeliverableRepository
{

    public function __construct(SaleRequestReader $requestReader)
    {
        parent::__construct($requestReader);
    }

    public function create(Request $request)
    {
        $sale = new Sale($this->getRequestReader()->readAttributes($request, Sale::class));
        if ($sale->save()) {
            $this->createMerchandises($request, $sale);
            $this->createDelivery($request, $sale);
        }
        return $sale;
    }

    /**
     * TODO Purchased merchandise and delivery information can be updated
     *
     * @param Request $request
     * @param Sale $sale
     */
    public function update(Request $request, Sale $sale)
    {

    }

    private function createMerchandises(Request $request, Sale $sale)
    {
        if ($this->hasMerchandises($request)) {
            foreach ($request->get(static::$requestAttributeMerchandises) as $merchandise) {
                $sale->createMerchandise(
                    $merchandise[self::$requestAttributeId],
                    $merchandise[MerchandiseSale::QUANTITY],
                    $merchandise[MerchandiseSale::RETAIL_PRICE]
                );
            }
        }
    }

}
