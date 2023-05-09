<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AdminAuthController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required'],
            'password' => ['required']
        ]);

        if (! $token = auth('admins')->attempt(['email' => $request->email,'password' => $request->password])) {
            return response()->json(["errors" => [
                "email" => ['Are you an admin??']
            ]],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admins')->factory()->getTTL() * 60,
            'user' => [
                'name' => auth('admins')->user()->name,
                'email' => auth('admins')->user()->email,
            ]
        ],Response::HTTP_OK);
    }
}
