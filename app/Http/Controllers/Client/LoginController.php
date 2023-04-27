<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        if (! $token = auth()->attempt(['email' => $request->email,'password' => $request->password])) {
            return response()->json(["errors" => [
                "email" => ['Email or Password is incorrect']
            ]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

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
    }
}
