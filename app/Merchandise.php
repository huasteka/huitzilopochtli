<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Merchandise extends Model
{

    use SoftDeletes;

    const RETAIL_PRICE = 'retail_price';
    const PURCHASE_PRICE = 'purchase_price';
    const IS_ACTIVE = 'is_active';

    const RELATIONSHIP_PRODUCT = 'product';
    
    protected $fillable = [
        self::RETAIL_PRICE,
        self::PURCHASE_PRICE,
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public static function readAttributes(Request $request)
    {
        return [
            self::RETAIL_PRICE => $request->get(self::RETAIL_PRICE),
            self::PURCHASE_PRICE => $request->get(self::PURCHASE_PRICE),
        ];
    }
    
}