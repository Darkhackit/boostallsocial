<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Mail\ConfirmMail;
use App\Mail\NewReferralMail;
use App\Models\Referral;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use illuminate\Support\Str;

class RegisterController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','unique:users','email:rfc'],
            'password' => ['required','confirmed','min:6']
        ]);



        DB::beginTransaction();
        $name = Str::random(5);
        try {
            $user = new User();
            $user->name = $name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->code = mt_rand(10000,99999);
            $user->code_expiry = Carbon::now()->addHours(24);
            $user->referral_id = $name;
            $user->api_key = Str::random();

            $user->save();

            if($user->id) {

                if($request->referral) {
                    $referral_user = User::query()->where('referral_id',$request->referral)->first();
                    if($referral_user) {
                        $referral = new Referral();
                        $referral->user_id = $user->id;
                        $referral->referrer = $referral_user->referral_id;

                        $referral->save();

                        Mail::to($referral_user->email)->send(new NewReferralMail($referral_user));
                    }
                }

            }

            Mail::to($request->email)->send(new ConfirmMail($user));

            DB::commit();

            return response()->json(['email' => $user->email],Response::HTTP_OK);

        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => [
                "server_error" => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
