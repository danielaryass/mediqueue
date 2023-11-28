<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;

        $response = response()->json([
            'success' => true,
            'message' => 'Login Berhasil',
            'token' => $token,
            'user' => $user,
            'roles' => $user->getRoleNames()->first(),
        ], 200);

        return $response->withCookie('token', $token, 60 * 24 * 7) 
                        ->withCookie('role', $user->getRoleNames()->first(), 60 * 24 * 7);
    }

    return response()->json([
        'success' => false,
        'message' => 'Email atau Password salah',
    ], 401);
}

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'password' => bcrypt($request->password),
        ]);
        $user->assignRole('user');
        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user->load('roles'),
        ]);
    }

    public function unauthorized()
    {
        return response()->json(['error' => 'Unauthorized', 'status' => 401], 401);
    }
}
