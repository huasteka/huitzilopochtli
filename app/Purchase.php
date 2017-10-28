<?php

namespace App;

final class Purchase extends Deliverable
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
        return $this->belongsToMany(Merchandise::class)->using(MerchandisePurchase::class);
    }

    public function createMerchandise($merchandiseId, $quantity, $purchasePrice = 0.00)
    {
        $this->merchandises()->attach($merchandiseId, [
            MerchandisePurchase::QUANTITY => $quantity,
            MerchandisePurchase::PURCHASE_PRICE => $purchasePrice,
        ]);
    }

}
