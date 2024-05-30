<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        $this->authorize('view', Permission::class);
        $permissions = Permission::all();
        return response()->json($permissions);
    }

    public function edit(Permission $permission){
        return response()->json($permission);
    }

    public function store(Request $request){
        $this->authorize('create', Permission::class);
        $request->validate([
            'name' => 'required|string'
        ]);

        $permission = Permission::create([
            'name' => $request->name
        ]);

        return response()->json($permission);
    }

    public function update(Request $request, $permissionId){
        $permission = Permission::findOrFail($permissionId);
        $this->authorize('update', $permission);
        $request->validate([
            'name' => 'required|string'
        ]);
    }

    public function destroy($permissionId){
        $permission = Permission::findOrFail($permissionId);
        $this->authorize('delete', $permission);
        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
