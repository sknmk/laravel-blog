<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class Api extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);
        $user = new User([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        $message['success'] = 'User created successfully.';
        return response()->json([
            'message' => $message
        ], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);
        $credentials = request(['email', 'password']);
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $message['token'] = $user->createToken('MyApp')->accessToken;
            $message['token_type'] = 'Bearer';
            $message['expires_at'] = Carbon::parse(Carbon::now('Europe/Istanbul')->addHours(3))->toDateTimeString();
            $message['success'] = 'Access granted.';
            $message['details'] = $request->user();

            return response()->json(['message' => $message]);
        } else {
            return response()->json(['error' => 'Access denied.'], 401);
        }
    }

    public function ping(Request $request): JsonResponse
    {
        if (Auth::guard('api')->check()) {
            return response()->json(['user' => auth()->user()]);
        } else {
            return response()->json(['error' => 'Access denied.'], 401);
        }
    }
}
