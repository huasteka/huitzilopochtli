<?php

namespace App\Http\Controllers;

use App\Services\JsonResponseFormatter;
use Laravel\Lumen\Routing\Controller as BaseController;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Encoder\Encoder;
use Neomerx\JsonApi\Encoder\EncoderOptions;

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

    /**
     * @param array $schemas
     * @return EncoderInterface
     */
    protected function createEncoder(array $schemas)
    {
        $encoderOptions = new EncoderOptions(JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return Encoder::instance($schemas, $encoderOptions);
    }

    protected function withJsonApi($json, $status = 200)
    {
        return response($json, $status, ['Content-Type' => 'application/json']);
    }

}
