<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Supplier extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'name',
        'trade_name',
        'legal_document_code',
    ];
    
    public static function readAttributes(Request $request)
    {
        return [
            'name' => $request->get('name'),
            'trade_name' => $request->get('trade_name'),
            'legal_document_code' => $request->get('legal_document_code'),
        ];
    }

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

}