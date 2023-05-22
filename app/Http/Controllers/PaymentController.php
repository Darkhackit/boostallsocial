<?php

namespace App\Http\Controllers;

use App\Mail\ReferralBonusMail;
use App\Models\Payment;
use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Shakurov\Coinbase\Facades\Coinbase;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        return \response()->json(Payment::query()->get()->map(function ($payment){
            return [
                'id' => $payment->transaction_id,
                'email' => $payment->user->email,
                'balance' => $payment->amount,
                'method' => $payment->method,
            ];
        }));
    }
    /**
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request,[
            'id' => ['required'],
            'amount' => ['required']
        ]);

        if($request->id == 2) {
            $charge = Coinbase::createCharge([
                'name' => auth()->user()->email,
                'description' => 'Adding Balance',
                'local_price' => [
                    'amount' => $request->amount,
                    'currency' => 'USD',
                ],
                'pricing_type' => 'fixed_price',
            ]);

            return response()->json($charge,Response::HTTP_OK);
        }
    }

    /**
     * @throws ValidationException
     */
    public function adminCreate(Request $request): JsonResponse
    {
        $this->validate($request,[
            'email' => ['required'],
            'amount' => ['required'],
            'paymentMethod' => ['required'],
            'bonus' => ['required'],
            'ip' => ['required','ipv4']
        ]);
        DB::beginTransaction();
        try {
            $user = User::query()->where('email',$request->email['email'])->first();
            $payment = new Payment();
            $payment->user_id = $user->id;
            $payment->amount = $request->amount;
            $payment->method = $request->paymentMethod;
            $payment->transaction_id = mt_rand(1000000,9999999);
            $payment->save();

            $location = Http::get("http://www.geoplugin.net/json.gp?ip=".$request->ip)->json();
            $total = $payment->amount * $location['geoplugin_currencyConverter'];
            $user->account()->update([
                'main' => $user->account->main + $payment->amount,
                'balance' => $user->account->balance + $total
            ]);
            if($request->bonus) {
                $referrer = Referral::query()->where('user_id',$user->id)->first();
                if($referrer) {
                    $referrer_user = User::query()->where('referral_id',$referrer->referrer)->first();
                    $referrer_user->account()->update([
                        'referral_balance' => $referrer_user->account->referral_balance + (0.05 * $total),
                        'referral_main' => $referrer_user->account->referral_main + (0.05 * $payment->amount)
                    ]);
                    Mail::to($referrer_user->email)->send(new ReferralBonusMail($referrer_user));
                }
            }
            DB::commit();
            return  \response()->json($payment,Response::HTTP_OK);

        }catch (\Exception $e) {
            DB::rollBack();
            return \response()->json(["errors" => [
                "server" => [$e->getMessage()]
            ]],Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
    public function getIp(): ?string
    {
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
//        return request()->ip(); // it will return the server IP if the client IP is not found using this method.
        return  "154.160.4.146";
    }
}
