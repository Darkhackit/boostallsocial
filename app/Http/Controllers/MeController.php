<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(["user" => [
            'name' => auth()->user()->name,
            'email' => auth()->user()->email,
            'balance' => number_format(auth()->user()->account->balance,2),
            'referral_balance' => number_format(auth()->user()->account->referral_balance,2),
            'currency' => auth()->user()->account->currency
        ]],Response::HTTP_OK);
    }
}
