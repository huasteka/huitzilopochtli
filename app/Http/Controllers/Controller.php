<?php

namespace App\Http\Controllers;

use App\Services\JsonResponseFormatter;
use Laravel\Lumen\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{

    protected function withJson(JsonResponseFormatter $jsonResponse, $status = 200)
    {
        return response()->json($jsonResponse->toArray(), $status);
    }

    protected function withStatus($status)
    {
        return response()->json([], $status);
    }

}
