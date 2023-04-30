<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','exists:users,email'],
            'password' => [
                'required',
                'confirmed',
                'min:6'
            ]
        ]);

        $user = User::whereEmail($request->email)->first();
        $user->password = Hash::make($request->password);
        $user->update();
        return response()->json($user,Response::HTTP_ACCEPTED);
    }
}

