<?php

namespace App\Http\Controllers\Api\Store;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;

        return Store::when(
            $search,
            fn($q) =>
            $q->where('name', 'like', "%$search%")
        )->paginate(10);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'level' => 'required|in:pusat,cabang,retail',
            'parent_store_id' => 'nullable|exists:stores,id'
        ]);

        $store = Store::create($data);

        // auto create admin & kasir
        $adminRole = Role::where('name', 'admin')->first();
        $kasirRole = Role::where('name', 'kasir')->first();

        User::create([
            'store_id' => $store->id,
            'role_id' => $adminRole->id,
            'name' => 'Admin ' . $store->name,
            'email' => 'admin_' . $store->id . '@toko.com',
            'password' => Hash::make('password')
        ]);

        User::create([
            'store_id' => $store->id,
            'role_id' => $kasirRole->id,
            'name' => 'Kasir ' . $store->name,
            'email' => 'kasir_' . $store->id . '@toko.com',
            'password' => Hash::make('password')
        ]);

        return response()->json(['message' => 'Store created']);
    }
}
