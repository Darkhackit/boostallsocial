<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','unique:users','email:rfc'],
            'password' => ['required','confirmed','min:6']
        ]);


        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = Str::random(5);
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->code = mt_rand(10000,99999);
            $user->code_expiry = Carbon::now()->addHours(24);
            $user->api_key = Str::random();

            $user->save();

            Mail::to($request->email)->send(new ConfirmMail($user));

            DB::commit();

            return response()->json($user,Response::HTTP_OK);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => [
                "server_error" => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
