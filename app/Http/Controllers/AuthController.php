<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function register(Request $request){
        
        try{

            foreach($request->rolesSelected as $key => $role){
                $role_list[$key] = $role['name'];
            }

            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password)
            ]);

            $user->syncRoles($role_list);

        }catch(\Exception $e){
            Log::error('Error registering user: '.$e->getMessage(), [
                'exception' => $e
            ]);
            return response()->json(['exception' => $e], 201);
        }

        return response()->json(['message' => 'User registered successfully'], 201);
    }

    public function login(Request $request){
        /*
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
        ]);*/


        try{
            $validateUser = Validator::make($request->all(),
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email','password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email and password does not match with our records'
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => $user->createToken("auth_token")->plainTextToken
            ], 200);
        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function logout(){
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });

        return response()->json([
            'status' => true,
            'message' => 'logout token'
        ], 200);
    }

    public function user(Request $request){
        return response()->json($request->user());
    }

}
