<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Role;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function test_kasir_can_create_sale()
    {
        $role = Role::create(['name' => 'kasir']);

        $store = Store::create([
            'name' => 'Toko A',
            'level' => 'retail'
        ]);

        $kasir = User::create([
            'name' => 'Kasir',
            'email' => 'kasir@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id,
            'store_id' => $store->id
        ]);

        $product = Product::create([
            'store_id' => $store->id,
            'name' => 'Kopi',
            'price' => 10000
        ]);

        // 🔑 Generate JWT dengan benar
        $token = JWTAuth::fromUser($kasir);

        $response = $this->withHeader(
            'Authorization',
            'Bearer ' . $token
        )->postJson('/api/sales', [
            'products' => [$product->id],
            'payment' => 20000
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment([
                'change' => 10000
            ]);
    }
}
