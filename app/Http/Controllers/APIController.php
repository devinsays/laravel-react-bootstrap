<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

abstract class ApiController extends Controller
{

    /**
     * Returns a generic success (200) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseSuccess($message = 'Success.')
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
        ], 200);
    }

    /**
     * Returns a resource updated success message (200) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseResourceUpdated($message = 'Resource updated.')
    {
        return response()->json([
            'status' => 200,
            'message' => $message,
        ], 200);
    }

    /**
     * Returns a resource created (201) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseResourceCreated($message = 'Resource created.')
    {
        return response()->json([
            'status' => 201,
            'message' => $message,
        ], 201);
    }

    /**
     * Returns a resource deleted (204) JSON response.
     *
     * @param  string $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseResourceDeleted($message = 'Resource deleted.')
    {
        return response()->json([
            'status' => 204,
            'message' => $message,
        ], 204);
    }

    /**
     * Returns an unauthorized (401) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnauthorized($errors = ['Unauthorized.'])
    {
        return response()->json([
            'status' => 401,
            'errors' => $errors,
        ], 401);
    }

    /**
     * Returns a unprocessable entity (422) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseUnprocessable($errors)
    {
        return response()->json([
            'status' => 422,
            'errors' => $errors,
        ], 422);
    }

    /**
     * Returns a server error (500) JSON response.
     *
     * @param  array $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function responseServerError($errors = ['Server error.'])
    {
        return response()->json([
            'status' => 500,
            'errors' => $errors
        ], 500);
    }
}
