<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if user is able to register successfully
     * @return void
     */
    public function testApiRegisterSuccess(): void
    {
        $params = [
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => '123456',
            'password_confirmation' => '123456',
        ];
        $response = $this->json('POST', '/api/register', $params);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test if endpoint is throwing error when param is missing
     * @return void
     */
    public function testApiRegisterError(): void
    {
        $params = [
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => '123456',
        ];
        $response = $this->json('POST', '/api/register', $params);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    '*' => []
                ],
            ]);
    }

    /**
     * Test if user can log in successfully
     * @return void
     */
    public function testApiLoginSuccess(): void
    {
        User::factory()->create([
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => Hash::make('123456'),
        ]);
        $params = [
            'email' => 'user10@email',
            'password' => '123456',
        ];
        $response = $this->json('POST', '/api/login', $params);

        $response->assertStatus(200)
            ->assertJsonStructure(['token']);
    }

    /**
     * Test wrong params for login
     * @return void
     */
    public function testApiLoginError(): void
    {
        User::factory()->create([
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => Hash::make('123456'),
        ]);
        $params = [
            'email' => 'user10@email',
            'password' => '1234567',
        ];
        $response = $this->json('POST', '/api/login', $params);

        $response->assertStatus(422);
    }

    /**
     * Test if user can log out when correct access token is sent
     * @return void
     */
    public function testApiLogoutSuccess(): void
    {
        $user = User::factory()->create([
            'name' => 'user10',
            'email' => 'user10@email',
            'password' => Hash::make('123456'),
        ]);
        $accessToken = $user->createToken('Laravel Password Grant Client')->accessToken;
        $this->withToken($accessToken);
        $response = $this->actingAs($user)->json('POST', '/api/logout');

        $response->assertStatus(200);
    }

    /**
     * Test if application is reporting error when access token is not provided for logout route
     * @return void
     */
    public function testApiLogoutError(): void
    {
        $response = $this->json('POST', '/api/logout');

        $response->assertStatus(401)
            ->assertJsonStructure(['message']);
    }
}
