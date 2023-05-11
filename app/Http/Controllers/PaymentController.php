<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Shakurov\Coinbase\Facades\Coinbase;

class PaymentController extends Controller
{
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
}
