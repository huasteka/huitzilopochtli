<?php

namespace App\Services\Product;

use Illuminate\Http\Request;

trait ProductRequestChecker
{
    
    use ProductPropertyBuilder;

    protected function hasMerchandise(Request $request)
    {
        return $request->has(static::$requestAttributeMerchandise);
    }

}
