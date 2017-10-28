<?php

namespace App\Services\Contactable;

use Illuminate\Http\Request;

trait ContactableRequestChecker
{

    use ContactablePropertyBuilder;

    protected function hasContacts(Request $request)
    {
        return $request->has(static::$requestAttributeContacts);
    }

}
