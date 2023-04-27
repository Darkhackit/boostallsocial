<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function __invoke(Request $request)
    {
        $this->validate($request,[
            'email' => ['required','email','unique:users'],
            'password' => ['required','confirmed','min:6']
        ]);
    }
}
