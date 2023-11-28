<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('pages.backend.user.index', compact ('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        
        $userRoleNames = $user->roles->pluck('name')->toArray();
        return view('pages.backend.user.edit', compact ('user', 'roles', 'userRoleNames'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();

    try {
        // Temukan pengguna
        $user = User::findOrFail($id);

        // Sinkronkan peran pengguna (pastikan $request->roles adalah array)
        if ($request->has('roles') && is_array($request->roles)) {
            $user->syncRoles($request->roles);
        }

        // Update pengguna dengan data yang baru
        $user->update($data);

        alert()->success('Success', 'Role has been updated');
        return redirect()->route('user.index');
    } catch (\Exception $e) {
        // Handle error, misalnya jika pengguna tidak ditemukan
        alert()->error('Error', 'User not found');
        return redirect()->route('user.index');
    }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
