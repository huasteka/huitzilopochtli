<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

final class Supplier extends Contactable
{

    use SoftDeletes;

    const NAME = 'name';
    const TRADE_NAME = 'trade_name';
    const LEGAL_DOCUMENT_CODE = 'legal_document_code';

    const RELATIONSHIP_CONTACTS = 'contacts';

    protected $fillable = [
        self::NAME,
        self::TRADE_NAME,
        self::LEGAL_DOCUMENT_CODE,
    ];

}
