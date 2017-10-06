<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

abstract class StandardController extends RestController
{

    abstract protected function parseRequest(Request $request);

    protected function validateRequest(Request $request, array $validationRules, array $newRules = [])
    {
        $this->validate($request, array_merge($validationRules, $newRules));
    }

}