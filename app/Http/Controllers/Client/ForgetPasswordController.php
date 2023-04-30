<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ForgetPasswordMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class ForgetPasswordController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','email:rfc','exists:users,email']
        ]);

        $user = User::whereEmail($request->email)->first();
        $user->code = mt_rand(10000,99999);
        $user->code_expiry = Carbon::now()->addHours(24);

        $user->update();

        Mail::to($user->email)->send(new ForgetPasswordMail($user));

        return response()->json($user,Response::HTTP_ACCEPTED);
    }
}
