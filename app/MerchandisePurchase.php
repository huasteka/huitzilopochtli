<?php
namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class MerchandisePurchase extends Pivot
{

    const PURCHASE_VALUE = 'purchase_value';

    const RELATIONSHIP_MERCHANDISE = 'merchandise';
    const RELATIONSHIP_PURCHASE = 'purchase';

    protected $fillable = [
        self::PURCHASE_VALUE,
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
