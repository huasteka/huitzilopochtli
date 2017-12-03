<?php

namespace App;

final class Sale extends Deliverable
{

    protected $fillable = [
        self::CODE,
        self::DESCRIPTION,
        self::GROSS_VALUE,
        self::NET_VALUE,
        self::DISCOUNT,
    ];

    public function merchandises()
    {
        return $this->belongsToMany(Merchandise::class)->using(MerchandiseSale::class);
    }

    public function createMerchandise($merchandiseId, $quantity, $retailPrice = 0.00, $clientId = null)
    {
        $merchandisePivot = [
            MerchandiseSale::QUANTITY => $quantity,
            MerchandiseSale::RETAIL_PRICE => $retailPrice,
        ];
        if (!is_null($clientId)) {
            $merchandisePivot[MerchandiseSale::CLIENT_ID] = $clientId;
        }
        $this->merchandises()->attach($merchandiseId, $merchandisePivot);
    }

}
