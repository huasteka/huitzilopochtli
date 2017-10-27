<?php

namespace App\Services;

use Illuminate\Http\Request;

interface RequestReader
{
    
    function readAttributes(Request $request, $type);
    
    function readCollection(Request $request, $type);

}
