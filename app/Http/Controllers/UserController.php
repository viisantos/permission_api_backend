<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(){
        $this->authorize('view', user::class);
        $users = User::with('roles')->get();

        return response()->json($users);
    }

    public function store(Request $request){
        $this->authorize('create', User::class);

        $request->validate([
            'name'     => 'required|string',
            'email'    => 'required|string|email',
            'password' => 'required|string',
            'roles'    => 'required|array'
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $user->syncRoles($request->roles);

        return response()->json($user, 201);
    }

    public function edit(User $user){
        $roles = Role::pluck('name','name')->all();
        $userRoles = $user->roles->pluck('name','name')->all();

        $data = [
            'user' => $user,
            'roles' => $roles,
            'userRoles' => $userRoles
        ];

        return response()->json($data);
    }

    public function update(Request $request, $userId){
        $user = User::findOrFail($userId);
        $this->authorize('update', $user);

        $request->validate([
            'name'     => 'sometimes|string',
            'email'    => 'sometimes|string|email',
            'password' => 'sometimes|string',
            'roles'    => 'sometimes|array'
        ]);

        if($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email')){
            $user->email = $request->email;
        }

        if($request->has('password')){
            $user->password =  Hash::make($request->password);
        }

        $user->save();

        if($request->has('roles')){
            $user->syncRoles($request->roles);
        }

        return response()->json($user);

    }

    public function destroy($userId){
        $user = User::findOrFail($userId);
        $this->authorize('delete', $user);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
