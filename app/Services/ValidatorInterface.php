<?php

namespace App\Services;

use Illuminate\Http\Request;

interface ValidatorInterface
{

    function getValidationRulesOnCreate(Request $request);

    function getValidationRulesOnUpdate(Request $request);

}
