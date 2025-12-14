<?php

namespace App\Exceptions;

use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CustomExceptionHandler extends Exception
{
    public function __construct($message = 'An error occurred', $code = 500)
    {
        parent::__construct($message, $code);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Customize the response for different types of exceptions
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'error' => 'Resource not found',
            ], 404);
        }
        if ($exception instanceof ValidationException) {
            return response()->json([
                'error' => 'Validation failed',
                'messages' => $exception->getMessage(),
            ], 422);
        }

        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'error' => 'Endpoint not found',
            ], 404);
        }

        if ($exception instanceof QueryException) {
            return response()->json([
                'error' => $exception->getMessage(),
            ], 404);
        }

        if ($exception instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'error' => 'Method not allowed',
            ], 405);
        }

        // Default response for other exceptions
        return response()->json([
            'error' => 'An error occurred',
            'message' => $exception->getMessage(),
        ], 500);
    }
}
