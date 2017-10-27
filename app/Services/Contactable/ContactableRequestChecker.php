<?php

namespace App\Services\Contactable;

use Illuminate\Http\Request;

trait ContactableRequestChecker
{

    use ContactableAttributeBuilder;

    protected function hasContacts(Request $request)
    {
        return $request->has(static::$requestAttributeContacts);
    }

}
