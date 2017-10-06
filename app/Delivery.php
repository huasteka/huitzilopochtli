<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

class Delivery extends Model
{

    use SoftDeletes;
    
    const SENT_AT = 'sent_at';
    const ARRIVED_AT = 'arrived_at';
    const DELIVERY_TIME = 'delivery_time';

    const RELATIONSHIP_DELIVERY_ADDRESS = 'address';
    
    protected $fillable = [
        self::SENT_AT,
        self::ARRIVED_AT,
        self::DELIVERY_TIME,
    ];
    
    public function address()
    {
        return $this->hasOne(DeliveryAddress::class, 'delivery_address_id');
    }

    public static function readAttributes(Request $request)
    {
        return [
            self::SENT_AT => $request->get(self::SENT_AT),
            self::ARRIVED_AT => $request->get(self::ARRIVED_AT),
            self::DELIVERY_TIME => $request->get(self::DELIVERY_TIME),
        ];
    }
    
}