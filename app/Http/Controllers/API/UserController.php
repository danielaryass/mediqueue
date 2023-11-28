<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class UserController extends Controller
{
     
    public function index(Request $request)
   {
   abort_if(Gate::denies('Akses Admin'),  response()->json(['error' => 'Forbidden'])); 
    $user = User::all();
    return response()->json([
        'data' => $user->load('roles'),
        'status' => 'success'
    ],200);
    }
    public function store(Request $request)
    {
        // abort_if(Gate::denies('Haho'), Response::HTTP_FORBIDDEN, response()->json(['error' => 'Forbidden']));
        $user = User::create($request->only('name', 'email', 'password'));
        $user->syncRoles($request->roles);
        return response()->json([
            'user' => $user->load('roles'),
        ]);
    }
    public function edit(User $user)
    {
        // abort_if(Gate::denies('Haho'), Response::HTTP_FORBIDDEN, response()->json(['error' => 'Forbidden']));
        $roles = Role::all();
        $userRoleNames = $user->roles->pluck('name')->toArray();
        return response()->json([
            'user' => $user->load('roles'),
            'roles' => $roles,
            'userRoleNames' => $userRoleNames,
        ]);
    }
    public function update(Request $request, User $user)
    {
        // abort_if(Gate::denies('Haho'), Response::HTTP_FORBIDDEN, response()->json(['error' => 'Forbidden']));
        // dd($request->name);
        $user->update($request->only('name', 'email'));
        if($request->roles == null){
            $user->syncRoles($user->roles);
        }else{
            $user->syncRoles($request->roles);
        }
        
        return response()->json([
            'user' => $user->load('roles'),
        ]);
    }
    public function destroy(User $user)
    {
        // abort_if(Gate::denies('Haho'), Response::HTTP_FORBIDDEN, response()->json(['error' => 'Forbidden']));
        $user->delete();
        return response()->json([
            'message' => 'User deleted successfully!',
        ]);
    }
}
