<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Delivery extends Model
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

    public function createAddress(DeliveryAddress $deliveryAddress)
    {
        $this->address()->save($deliveryAddress);
    }
    
}