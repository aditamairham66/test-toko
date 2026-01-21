<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login()
    {
        $role = Role::create([
            'name' => 'super_admin'
        ]);

        User::create([
            'name' => 'Test User',
            'email' => 'test@mail.com',
            'password' => Hash::make('password'),
            'role_id' => $role->id
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@mail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure(['token', 'user']);
    }
}
