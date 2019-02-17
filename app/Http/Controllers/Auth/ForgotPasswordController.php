<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends APIController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function email(Request $request)
    {

        $validator = Validator::make(
            $request->only('email'),
            ['email' => 'required|string|email|max:255|exists:users,email'],
            ['exists' => "We couldn't find an account with that email."]
        );

        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors());
        }

        $response = $this->sendResetLinkEmail($request);

        if ($response) {
            return $this->responseSuccess('Email reset link sent.');
        } else {
            return $this->responseServerError();
        }
    }
}
