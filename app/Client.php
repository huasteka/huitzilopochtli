<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;

final class Client extends Model
{

    use SoftDeletes;

    const NAME = 'name';
    const LEGAL_DOCUMENT_CODE = 'legal_document_code';

    const RELATIONSHIP_CONTACTS = 'contacts';

    protected $fillable = [
        self::NAME,
        self::LEGAL_DOCUMENT_CODE,
    ];

    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    protected static function validationRules()
    {
        return [
            self::NAME => 'required',
        ];
    }

    public static function validationRulesOnCreate()
    {
        return array_merge(self::validationRules(), [
            self::LEGAL_DOCUMENT_CODE => 'required|unique:clients'
        ]);
    }

    public static function validationRulesOnUpdate()
    {
        return array_merge(self::validationRules(), [
            self::LEGAL_DOCUMENT_CODE => 'required'
        ]);
    }

    public static function readAttributes(Request $request)
    {
        return [
            self::NAME => $request->get(self::NAME),
            self::LEGAL_DOCUMENT_CODE => $request->get(self::LEGAL_DOCUMENT_CODE),
        ];
    }

}
