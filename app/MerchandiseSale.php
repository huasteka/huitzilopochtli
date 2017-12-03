<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class MerchandiseSale extends Pivot
{

    const QUANTITY = 'quantity';
    const RETAIL_PRICE = 'retail_price';
    const CLIENT_ID = 'client_id';

    const RELATIONSHIP_CLIENT = 'client';

    protected $fillable = [
        self::QUANTITY,
        self::RETAIL_PRICE,
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, self::CLIENT_ID);
    }

}
