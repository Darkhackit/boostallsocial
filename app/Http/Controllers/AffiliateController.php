<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AffiliateController extends Controller
{
    public function referrals(): JsonResponse
    {
        $referrals = Referral::query()->where('referrer',auth()->user()->referral_id)->get();
        return response()->json($referrals->map(function ($referrer) {
            return [
                'email' => $referrer->user->email,
                'joined'=> $referrer->user->created_at->format('Y-m-d'),
                'earning' => $referrer->user->account->balance === 0 ? 0 : number_format($referrer->user->account->balance * 0.05,2),
                'currency' => $referrer->user->account->currency,
            ];
        }),Response::HTTP_OK);
    }
}
