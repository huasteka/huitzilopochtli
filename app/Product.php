<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Product extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name', 
        'code', 
        'description', 
        'retail_price', 
        'purchase_price',
    ];
    
    public static function readAttributes(Request $request)
    {
        return [
            'name' => $request->get('name'),
            'code' => $request->get('code'),
            'description' => $request->get('description'),
            'retail_price' => $request->get('retail_price'),
            'purchase_price' => $request->get('purchase_price'),
        ];
    }

}