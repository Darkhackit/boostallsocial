<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ConfirmCodeController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','exists:users,email'],
            'code' => ['required']
        ]);
        $user = User::whereEmail($request->email)->first();
        if(now()->gt($user->code_expiry)) {
            return response()->json(["errors" => [
                "code" => ["The entered code has expired"]
            ]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        if($user->code !== $request->code) {
            return response()->json(["errors" => [
                "code" => ["The entered code is incorrect"]
            ]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user->code = "";
        $user->email_verified_at = Carbon::now();

        $user->update();

        $token = auth()->login($user);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => [
                'name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'balance' => auth()->user()->account->balance,
                'currency' => auth()->user()->account->currency
            ]
        ],Response::HTTP_OK);
    }
}
