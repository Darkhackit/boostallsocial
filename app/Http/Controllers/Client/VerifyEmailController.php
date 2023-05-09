<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class VerifyEmailController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required','exists:users,email']
        ]);
        $user = User::query()->where('email',$request->email)->first();
        $user->code_expiry = Carbon::now()->addHours(24);
        $user->update();
        Mail::to($request->email)->send(new ConfirmMail($user));

        return response()->json([
            'email' => $user->email
        ],Response::HTTP_OK);
    }
}
