<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    public function register(Request $request){
        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string',
            'password' => 'required|string'
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        try{
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;
    }catch(\Exception $e){
        echo $e;
    }

        return response()->json([
            'access_token' => $token,
            'token_type'   => 'Bearer'
        ]);

    }

    public function logout(Request $request){
        //$request->user()->currentAccessToken()->delete();
        //$user = request()->user();
        dd($request);

        return response()->json(['message' => 'Logged out']);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }


}
