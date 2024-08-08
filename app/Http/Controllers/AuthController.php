<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request) 
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()){
            return response([
                'status' => 'error',
                'message' => 'Incorrect data', 
                'errors' => $validator->errors()
            ], 400);
        }
        
        if(!Auth::attempt($request->only(['email', 'password']))){
            return response([
                'status' => 'error',
                'message' => 'Incorrect data' 
            ], 401);
        }

        $user = User::where('email', $data['email'])->first();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Login succcess',
            'token' => $token
        ]);
    }
}
