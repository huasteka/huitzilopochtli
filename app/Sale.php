<?php

namespace App;

final class Sale extends Deliverable
{

    const RELATIONSHIP_MERCHANDISES_SOLD = 'merchandisesSold';

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

    public function merchandisesSold()
    {
        return $this->hasMany(MerchandiseSale::class);
    }

    public function createMerchandise($merchandiseId, $quantity, $retailPrice = 0.00, $clientId = null)
    {
        $this->merchandises()->attach($merchandiseId, $this->createMerchandisePivot($quantity, $retailPrice, $clientId));
    }

    public function updateMerchandise($merchandiseId, $quantity, $retailPrice = 0.00, $clientId = null)
    {
        $this->merchandises()->updateExistingPivot($merchandiseId, $this->createMerchandisePivot($quantity, $retailPrice, $clientId));
    }

    private function createMerchandisePivot($quantity, $retailPrice = 0.00, $clientId = null)
    {
        $merchandisePivot = [
            MerchandiseSale::QUANTITY => $quantity,
            MerchandiseSale::RETAIL_PRICE => $retailPrice,
        ];
        if (!is_null($clientId)) {
            $merchandisePivot[MerchandiseSale::CLIENT_ID] = $clientId;
        }
        return $merchandisePivot;
    }

}
