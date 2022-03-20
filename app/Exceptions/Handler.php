<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        $parentRender = parent::render($request, $e);

        if ($parentRender instanceof JsonResponse) {
            return $parentRender;
        }

        $statusCode = $parentRender->getStatusCode();
        switch ($statusCode) {
            case Response::HTTP_NOT_FOUND:
                $errorMessage = 'Not found';
                break;
            default:
                $errorMessage = $e->getMessage();
                break;
        }

        if (empty($errorMessage)) {
            $errorMessage = 'There was an error when processing the request';
        }

        $jsonResponse = [
            'errors' => [[
                'status' => $statusCode,
                'message' => $errorMessage,
            ]],
        ];
        return new JsonResponse($jsonResponse);
    }
}
