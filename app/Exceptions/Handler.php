<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // dd($exception);
        // dd($exception instanceof ModelNotFoundException);
        if ($request->wantsJson() || $request->isJson()) {

            $response = [];

            // If this exception is an instance of HttpException
            if ($this->isHttpException($exception)) {
                // Grab the HTTP status code from the Exception
                $response["internalMessage"] = $exception->getMessage() ?: "Not Found";
                $code = $exception->getStatusCode();
            }

            if ($exception instanceof ValidationException) {
                $response["message"] = current(current($exception->errors()));
                $response["errors"]["errorMessage"] = "Invalid Data";
                $response["errors"]["errorDetails"] = $exception->errors();
                $code = 422;
            }

            if ($exception instanceof AuthenticationException ) {
                $response["message"] = "Unauthenticated.";
                $code = 401;
            }

            if ($exception instanceof ModelNotFoundException) {
                $response["message"] = $exception->getModel() . " not found with the id: " . implode(", ", $exception->getIds());
                $code = 404;
            }
            
            // Return a JSON response with the response array and status code
            $response["code"] = $code;
            return response()->json($response, 200);
        }
        return parent::render($request, $exception);
    }
}
