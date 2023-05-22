<?php

namespace App\Http\Controllers;

use App\Models\AffiliatePayment;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use PHPUnit\TextUI\Exception;

class SocialMediaOrder extends Controller
{
    public array $data;
    /**
     * @throws ValidationException
     */
    public function create(Request $request)
    {
        $this->validate($request,[
            'country' => ['required']
        ]);
        $target_country = Country::query()->where('id',$request->country['id'])->first();
        $social_media = $target_country->social_media;
        $provider = $target_country->provider;

        $total = $request->quantity * $target_country->price / 1000;

        if($social_media->type === 'default') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max]
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity
            ];
        }
        if($social_media->type === 'mention_username') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'username' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'username' => $request->username
            ];
        }
        if($social_media->type === 'mention_usernames') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'usernames' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'usernames' => $request->usernames
            ];
        }
        if($social_media->type === 'mention_comments') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'comments' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'comments' => $request->comments
            ];
        }
        if($social_media->type === 'custom_comments') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'comments' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'comments' => $request->comments
            ];
        }
        if($social_media->type === 'custom_likes') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'username' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'username' => $request->username
            ];
        }
        if($social_media->type === 'poll') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'answer_number' => ['required']
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'answer_number' => $request->answer_number
            ];
        }
        if($social_media->type === 'comment_replies') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
            ];
        }
        if($social_media->type === 'mentions_with_hashtags') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'usernames' => ['required'],
                'hashtags' => ['required'],
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'usernames' => $request->usernames,
                'hashtags' => $request->hashtags,
            ];
        }
        if($social_media->type === 'mentions_hashtag') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'hashtag' => ['required'],
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'hashtag' => $request->hashtag,
            ];
        }
        if($social_media->type === 'mentions_media_likers') {
            $this->validate($request,[
                'link' => ['required','url'],
                'quantity' => ['required','integer','min:'.$target_country->min ,'max:'.$target_country->max],
                'media' => ['required'],
            ]);

            $this->data = [
                'key' => $provider->api_key,
                'action' => 'add',
                'service' => $target_country->service_id,
                'link' => $request->link,
                'quantity' => $request->quantity,
                'media' => $request->media,
            ];
        }

        if($request->payment === 1) {
            if ((float)$total > auth()->user()->account->main) {

                return response()->json(["error" => [
                    "You dont have enough balance to purchase"
                ]],Response::HTTP_EXPECTATION_FAILED);
            }
        }
        if($request->payment === 2) {
            if ((float)$total > auth()->user()->account->referral_main) {

                return response()->json(["error" => [
                    "You dont have enough balance to purchase"
                ]],Response::HTTP_EXPECTATION_FAILED);
            }
        }
        DB::beginTransaction();
        try {
            $response = Http::get($target_country->provider->url,$this->data)->json();
            $location = Http::get("http://www.geoplugin.net/json.gp?ip=".$this->getIp())->json();
            $local_balance = $total * $location['geoplugin_currencyConverter'];

            if($response) {
                if(array_key_exists('error',$response)) {
                    return response()->json(['server_error' => $response['error']],Response::HTTP_INTERNAL_SERVER_ERROR);
                }
                if(array_key_exists('order',$response)) {
                    if ($request->payment === 1) {
                        auth()->user()->account()->update([
                            'main' => auth()->user()->account->main - $total,
                            'balance' => auth()->user()->account->balance - $local_balance
                        ]);
                    }
                    if ($request->payment === 2) {
                        auth()->user()->account()->update([
                            'referral_main' => auth()->user()->account->referral_main - $total,
                            'referral_balance' => auth()->user()->account->referral_balance - $local_balance
                        ]);

                        $affiliate_payment = new AffiliatePayment();
                        $affiliate_payment->user_id = auth()->id();
                        $affiliate_payment->payment_key = mt_rand(10000,99999);
                        $affiliate_payment->channel = "social media service";
                        $affiliate_payment->amount = $local_balance ;
                        $affiliate_payment->product = $target_country->social_media->name;
                        $affiliate_payment->save();
                    }

                    $social_order = new \App\Models\SocialMediaOrder();
                    $social_order->country_id = $target_country->id;
                    $social_order->order_key = mt_rand(10000,99999);
                    $social_order->quantity = $request->quantity;
                    $social_order->status = 'pending';
                    $social_order->link = $request->link;
                    $social_order->user_id = auth()->id();

                    $social_order->save();

                    DB::commit();
                    return response()->json([
                        'success' => true
                    ],Response::HTTP_OK);
                }
            }


        }catch (Exception $exception) {
            DB::rollBack();
            return response()->json(['server_error' => $exception->getMessage()],Response::HTTP_INTERNAL_SERVER_ERROR);
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
        return  "196.61.40.241";
    }
}
