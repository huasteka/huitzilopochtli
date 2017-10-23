<?php
namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class MerchandisePurchase extends Pivot
{

    const QUANTITY = 'quantity';
    const PURCHASE_PRICE = 'purchase_price';

    const RELATIONSHIP_MERCHANDISE = 'merchandise';
    const RELATIONSHIP_PURCHASE = 'purchase';

    protected $fillable = [
        self::QUANTITY,
        self::PURCHASE_PRICE,
    ];

    public function merchandise()
    {
        return $this->hasMany(Merchandise::class, 'merchandise_id');
    }

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'purchase_id');
    }

}
