<?php

namespace App\Services;

use Illuminate\Http\Request;

abstract class AbstractRequestReader implements RequestReader
{

    public function readCollection(Request $request, $type)
    {
        return [];
    }

}
