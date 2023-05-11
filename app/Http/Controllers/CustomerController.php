<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    public function getCustomers(): JsonResponse
    {
        return response()->json(User::query()->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'balance' => $user->account->main,
                'currency' => $user->account->currency
            ];
        }));
    }

    /**
     * @throws ValidationException
     */
    public function createCustomer(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','email','unique:users'],
            'password' => ['required','confirmed'],
        ]);
        $name = Str::random(5);
        $user = new User();
        $user->name = $name;
        $user->email = $request->email;
        $user->referral_id = $name;
        $user->api_key = Str::random();
        $user->email_verified_at = now();
        $user->password = Hash::make($request->password);

        $user->save();
        return response()->json($user,Response::HTTP_OK);

    }
    public function showCustomer(User $user): JsonResponse
    {
        return response()->json([
            'id' => $user->id,
            'email' => $user->email
        ],Response::HTTP_OK);
    }

    /**
     * @throws ValidationException
     */
    public function updateCustomer(Request $request, User $user): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required','email','unique:users,email,'.$user->id],
        ]);
        $user->email = $request->email;

        $user->update();

        return response()->json($user,Response::HTTP_OK);
    }
}
