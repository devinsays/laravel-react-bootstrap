<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use App\User;

class LoginTest extends TestCase
{

    use DatabaseMigrations;

    private $api = '/api/v1/auth';

    /** @test */
    public function registeredUserCanLogin()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('password')
        ]);

        $credentials = [
            'email' => $user['email'],
            'password' => 'password'
        ];

        $response = $this->json('POST', $this->api .'/login', $credentials);
        $response->assertStatus(200);
        $this->assertNotNull($response->getData()->access_token);
    }

    /** @test */
    public function unregisteredUserCannotLogin()
    {
        $credentials = [
            'email' => 'unregistered@example.com',
            'password' => 'password'
        ];

        $response = $this->json('POST', $this->api .'/login', $credentials);
        $response->assertStatus(401)->assertJson([
            'status' => 401,
            'errors' => [
                'Unauthorized.'
            ]
        ]);
    }
}
