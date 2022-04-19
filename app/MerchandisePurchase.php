<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class MerchandisePurchase extends Pivot
{

    const QUANTITY = 'quantity';
    const PURCHASE_PRICE = 'purchase_price';
    const SUPPLIER_ID = 'supplier_id';
    const MERCHANDISE_ID = 'merchandise_id';
    
    const RELATIONSHIP_SUPPLIER = 'supplier';
    const RELATIONSHIP_MERCHANDISE = 'merchandise';

    protected $fillable = [
        self::SUPPLIER_ID,
        self::QUANTITY,
        self::PURCHASE_PRICE,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, self::SUPPLIER_ID);
    }

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class, self::MERCHANDISE_ID);
    }

}
