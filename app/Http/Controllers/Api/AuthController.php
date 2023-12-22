<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    //
    public function index() {
        $user = auth()->user();

        return responseJson($user);
    }

    public function store() {
        $validated = request()->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($validated)) {
            return responseJson([
                'message' => 'Invalid credentials'
            ], null, 401);
        }

        $user = User::where('username', $validated['username'])->first();

        return responseJson([
            'user' => $user,
            'token' => $user->createToken('authToken')->plainTextToken
        ]);
    }

    public function destroy() {
        auth()->user()->tokens()->delete();
        return responseJson(null, 'Logged out');
    }
}
