<?php

namespace App\Http\Controllers;

use App\Services\JsonResponseFormatter;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    protected function withJson(JsonResponseFormatter $jsonResponse, $status = 200)
    {
        return response()->json($jsonResponse->toArray(), $status);
    }

}
