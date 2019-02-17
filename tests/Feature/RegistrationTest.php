<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use App\User;

class RegistrationTest extends TestCase
{

    use DatabaseMigrations;

    private $api = '/api/v1/auth';

    /** @test */
    public function newUserCanRegister()
    {
        $user = [
            'name' => 'Name',
            'email' => 'name@example.com',
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ];

        $response = $this->json('POST', $this->api . '/register', $user);
        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'email' => 'name@example.com'
        ]);
    }

    /** @test */
    public function existingUserCannotRegister()
    {
        $existing_user = factory(User::class)->create();
        $user = [
            'name' => $existing_user->name,
            'email' => $existing_user->email,
            'password' => 'foobar',
            'password_confirmation' => 'foobar'
        ];
        $response = $this->json('POST', $this->api . '/register', $user);

        $response->assertStatus(422)->assertJson([
            'status' => 422,
            'errors' => [
                'email' => ['The email has already been taken.']
            ]
        ]);
    }
}
