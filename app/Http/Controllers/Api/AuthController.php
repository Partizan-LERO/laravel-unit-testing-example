<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return new JsonResponse($validator->errors(), '400');
        }

        $credentials = $request->only('email', 'password');

        if (Auth::once($credentials)) {
            $user = Auth::user();
            return JsonResponse::create(['api_token' => $user->api_token], 200);
        }

        return JsonResponse::create('User does not exist', 201);
    }
}
