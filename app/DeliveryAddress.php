<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{

    const IS_DEFAULT = 'is_default';
    
    const RELATIONSHIP_CONTACT = 'contact';
    
    protected $fillable = [
        self::IS_DEFAULT,
    ];

    public function contact()
    {
        return $this->morphOne(Contact::class, 'contactable');
    }

}
