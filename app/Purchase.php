<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Purchase extends Model
{

    use SoftDeletes;

    const CODE = 'code';
    const DESCRIPTION = 'description';
    const GROSS_VALUE = 'gross_value';
    const NET_VALUE = 'net_value';
    const DISCOUNT = 'discount';
    
    const RELATIONSHIP_MERCHANDISES = 'merchandises';
    const RELATIONSHIP_DELIVERY = 'delivery';

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

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'purchase_id');
    }

    public static function readAttributes(Request $request)
    {
        return [
            self::CODE => $request->get(self::CODE),
            self::DESCRIPTION => $request->get(self::DESCRIPTION),
            self::GROSS_VALUE => $request->get(self::GROSS_VALUE),
            self::NET_VALUE => $request->get(self::NET_VALUE),
            self::DISCOUNT => $request->get(self::DISCOUNT),
        ];
    }

}