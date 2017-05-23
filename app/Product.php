<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Product extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 
        'code', 
        'description', 
        'retail_price', 
        'purchase_price'
    ];

}