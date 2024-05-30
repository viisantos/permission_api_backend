<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(){
        $this->authorize('view', Role::class);
        $roles = Role::with('permissions')->get();

        return response()->json($roles);
    }

    public function store(Request $request){
        $this->authorize('create', Role::class);

        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array',
        ]);

        $role = Role::create([
            'name' => $request->name
        ]);

        $role->syncPermissions($request->permissions);

        return response()->json($role, 201);
    }

    public function update(Request $request, $roleId){
        $role = Role::findOrFail($roleId);
        $this->authorize('update', $role);

        $request->validate([
            'name' => 'required|string',
            'permissions' => 'required|array'
        ]);

        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions);

        return response()->json($role, ['message' => 'Role updated successfully']);
    }

    public function destroy($roleId){
        $role = Role::findOrFail($roleId);
        $this->authorize('delete', $role);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully']);
    }

    public function addPermissionToRole($roleId){
        $permissions = Permission::get();
        $role = Role::findOrFail($roleId);

        $rolePermissions = DB::table('role_has_permissions')
                             ->where('role_has_permissions.role_id', $roleId)
                             ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                             ->all();

        $data = [
            'role' => $role,
            'permissions' => $permissions,
            'rolePermissions' => $rolePermissions
        ];

        return response()->json($data);
    }

    public function givePermissionToRole(Request $request, $roleId){
        $request->validate([
            'permission' => 'required'
        ]);

        $role = Role::findOrFail($roleId);
        $role->syncPermissions($request->permission);

        return response()->json(['status' => 'Permissions added to role']);
    }

}
