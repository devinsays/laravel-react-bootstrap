<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use App\User;

class LogoutTest extends TestCase
{

    use DatabaseMigrations;

    private $api = '/api/v1/auth';

    /** @test */
    public function tokenCanBeLoggedOut()
    {
        $user = factory(User::class)->create([
            'password' => Hash::make('password')
        ]);

        $credentials = [
            'email' => $user['email'],
            'password' => 'password'
        ];

        $response = $this->json('POST', $this->api . '/login', $credentials);
        $token = $response->getData()->access_token;

        $response = $this->json(
            'POST',
            $this->api . '/logout',
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $message = $response->getData()->message;
        $this->assertEquals("Successfully logged out.", $message);
    }
}
