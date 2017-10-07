<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class DeliveryAddress extends Model
{

    const IS_DEFAULT = 'is_default';
    
    const RELATIONSHIP_CONTACTS = 'contacts';
    
    protected $fillable = [
        self::IS_DEFAULT,
    ];

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    
    public static function readAttributes(Request $request)
    {
        return [
            self::IS_DEFAULT => $request->get(self::IS_DEFAULT, false),
        ];
    }

    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($deliveryAddress) {
            $deliveryAddress->contacts()->delete();
        });
    }
    
}
