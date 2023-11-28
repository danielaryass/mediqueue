<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function index(Request $request)
    {
    $roles = Role::all();
        return response()->json([
            'roles' => $roles,
        ]);
    }
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissionNames = $role->permissions->pluck('name')->toArray();
        return response()->json([
            'role' => $role->load('permissions'),
            'permissions' => $permissions,
            'rolePermissionNames' => $rolePermissionNames,
        ]);
    }
    public function update(Request $request, Role $role)
    {
        $role->update($request->only('name'));
        if($request->permissions == null){
            $role->syncPermissions($role->permissions);
        }else{
            $role->syncPermissions($request->permissions);
        }
        
        return response()->json([
            'role' => $role->load('permissions'),
        ]);
    }

    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->givePermissionTo($request->permissions);
        return response()->json([
            'role' => $role->load('permissions'),
        ]);
    }
    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json([
            'success' => true,
            'status' => 200,
            'message' => 'Role has been deleted',
        ]);
    }

}
