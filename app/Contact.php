<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

final class Contact extends Model
{
    
    const PHONE = 'phone';
    const ADDRESS = 'address';
    const ADDRESS_COMPLEMENT = 'address_complement';
    const POSTAL_CODE = 'postal_code';
    const CITY = 'city';
    const REGION = 'region';
    const COUNTRY = 'country';
    
    const CONTACTABLE_ID = 'contactable_id';
    const CONTACTABLE_TYPE = 'contactable_type';

    protected $fillable = [
        self::PHONE,
        self::ADDRESS,
        self::ADDRESS_COMPLEMENT,
        self::POSTAL_CODE,
        self::CITY,
        self::REGION,
        self::COUNTRY,
    ];

    protected $hidden = [
        self::CONTACTABLE_ID,
        self::CONTACTABLE_TYPE,
    ];

}
