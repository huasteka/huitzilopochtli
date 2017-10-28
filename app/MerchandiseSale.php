<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class MerchandiseSale extends Pivot
{

    const QUANTITY = 'quantity';
    const RETAIL_PRICE = 'retail_price';

    protected $fillable = [
        self::QUANTITY,
        self::RETAIL_PRICE,
    ];

}
