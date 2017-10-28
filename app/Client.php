<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;

final class Client extends Contactable
{

    use SoftDeletes;

    const NAME = 'name';
    const LEGAL_DOCUMENT_CODE = 'legal_document_code';

    protected $fillable = [
        self::NAME,
        self::LEGAL_DOCUMENT_CODE,
    ];

}
