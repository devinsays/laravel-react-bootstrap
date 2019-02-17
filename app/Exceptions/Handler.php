<?php

namespace App\Exceptions;

use App;
use Exception;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // Catches known exceptions on API routes.
        if ($request->is('api/*')) {
            $response = $this->apiResponse($request, $exception);
            if ($response) {
                return response()->json($response, $response['status']);
            }
        }

        // Catches unknown exceptions on API routes in production.
        if ($request->is('api/*') && App::environment('production')) {
            return [
                'status' => 500,
                'message' => 'Server error.'
            ];
        }

        return parent::render($request, $exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return array|boolean $response
     */
    public function apiResponse($request, Exception $exception)
    {

        // Method not allowed.
        if (get_class($exception) === "Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException") {
            $method = $request->method();
            return [
                'status' => 405,
                'message' =>  "$method method is not allowed for the requested route."
            ];
        }

        // Resource not found.
        if (get_class($exception) === "Illuminate\Database\Eloquent\ModelNotFoundException") {
            return [
                'status' => 404,
                'message' => 'Resource not found.'
            ];
        }

        // If it's not one of these known exceptions, return false.
        return false;
    }
}
