<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

final class Contact extends Model
{

    protected $fillable = [
        'phone',
        'address',
        'address_complement',
        'postal_code',
        'city',
        'region',
        'country',
    ];

    protected $hidden = [
        'contactable_id',
        'contactable_type'
    ];

    public static function readAttributes($requestAttributes)
    {
        return [
            'phone' => $requestAttributes['phone'],
            'address' => $requestAttributes['address'],
            'address_complement' => $requestAttributes['address_complement'],
            'postal_code' => $requestAttributes['postal_code'],
            'city' => $requestAttributes['city'],
            'region' => $requestAttributes['region'],
            'country' => $requestAttributes['country'],
        ];
    }

}