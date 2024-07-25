<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function testCreateUser()
    {

        $user = User::find(1);
        $token = $user->createToken('TestToken')->plainTextToken;
        $data = [
            'name' => 'testing',
            'email' => 'testing@gmail.com',
            'password' => 'testing123',
            'role_id' => 1
        ];

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->postJson('http://127.0.0.1:8000/api/user', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id', 'name', 'email', 'role_id', 'created_at', 'updated_at'
            ]
        ]);
    }

    public function testLogin()
    {
        $data = [
            'email' => 'fkoryanto@gmail.com',
            'password' => 'ferdi123'
        ];

        $response = $this->postJson('http://127.0.0.1:8000/api/login', $data);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'message',
                'user' => [
                    'id', 'name', 'email', 'role_id'
                ],
                'token'
            ]
        ]);
        $response->dump();
    }
}
