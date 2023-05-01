<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required'],
            'password' => ['required']
        ]);

        $user = User::where(['email' => $request->email])->first();
        if($user) {
            if(Hash::check($request->password,$user->password)) {

                if($user->email_verified_at) {

                    $token = auth()->login($user);

                    return response()->json([
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => auth('api')->factory()->getTTL() * 60,
                        'user' => [
                            'name' => auth()->user()->name,
                            'email' => auth()->user()->email,
                            'balance' => auth()->user()->balance,
                        ]
                    ],Response::HTTP_OK);
                }else {
                    return response()->json(["errors" => [
                        "email" => ['Verify Email']
                    ]],Response::HTTP_EXPECTATION_FAILED);
                }
            }else {
                return response()->json(["errors" => [
                    "email" => ['Email or Password is incorrect']
                ]],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }else {
            return response()->json(["errors" => [
                "email" => ['Email or Password is incorrect']
            ]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }


    }
}
