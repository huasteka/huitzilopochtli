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
    
    const CONTACTABLE_ID = '';
    const CONTACTABLE_TYPE = '';

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

    public static function readAttributes($requestAttributes)
    {
        return [
            self::PHONE => $requestAttributes[self::PHONE],
            self::ADDRESS => $requestAttributes[self::ADDRESS],
            self::ADDRESS_COMPLEMENT => $requestAttributes[self::ADDRESS_COMPLEMENT],
            self::POSTAL_CODE => $requestAttributes[self::POSTAL_CODE],
            self::CITY => $requestAttributes[self::CITY],
            self::REGION => $requestAttributes[self::REGION],
            self::COUNTRY => $requestAttributes[self::COUNTRY],
        ];
    }

}