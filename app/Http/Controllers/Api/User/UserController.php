<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function storeKasir(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        $role = Role::where('name', 'kasir')->first();

        User::create([
            'store_id' => auth('api')->user()->store_id,
            'role_id' => $role->id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json(['message' => 'Kasir created']);
    }

    public function listKasir()
    {
        return User::where('store_id', auth('api')->user()->store_id)
            ->whereHas('role', fn($q) => $q->where('name', 'kasir'))
            ->paginate(10);
    }

    public function updateProfile(Request $request)
    {
        auth('api')->user()->update(
            $request->only('name', 'email')
        );

        return response()->json(['message' => 'Profile updated']);
    }
}
