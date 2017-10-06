<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Product extends Model
{

    use SoftDeletes;
    
    const NAME = 'name';
    const CODE = 'code';
    const DESCRIPTION = 'description';

    protected $fillable = [
        self::NAME, 
        self::CODE, 
        self::DESCRIPTION,
    ];
    
    protected static function validationRules()
    {
        return [
            self::NAME => 'required',
        ];
    }
    
    public static function validationRulesOnCreate()
    {
        return array_merge(self::validationRules(), [
            self::CODE => 'required|unique:products',
        ]);
    }
    
    public static function validationRulesOnUpdate()
    {
        return array_merge(self::validationRules(), [
            Product::CODE => 'required',
        ]);
    }
    
    public static function readAttributes(Request $request)
    {
        return [
            self::NAME => $request->get(self::NAME),
            self::CODE => $request->get(self::CODE),
            self::DESCRIPTION=> $request->get(self::DESCRIPTION),
        ];
    }

}
