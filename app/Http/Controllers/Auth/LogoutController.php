<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\ApiController;

class LogoutController extends APIController
{

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->responseSuccess('Successfully logged out.');
    }
}
