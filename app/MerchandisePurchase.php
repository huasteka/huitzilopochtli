<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class MerchandisePurchase extends Pivot
{

    const QUANTITY = 'quantity';
    const PURCHASE_PRICE = 'purchase_price';
    const SUPPLIER_ID = 'supplier_id';
    
    const RELATIONSHIP_SUPPLIER = 'supplier';

    protected $fillable = [
        self::QUANTITY,
        self::PURCHASE_PRICE,
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, self::SUPPLIER_ID);
    }

}
