<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Merchandise extends Model
{

    use SoftDeletes;

    const RETAIL_PRICE = 'retail_price';
    const PURCHASE_PRICE = 'purchase_price';

    const RELATIONSHIP_PRODUCT = 'product';
    
    protected $fillable = [
        self::RETAIL_PRICE,
        self::PURCHASE_PRICE,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function createProduct(array $attributes)
    {
        $this->product()->associate(new Product($attributes));
    }
    
}
