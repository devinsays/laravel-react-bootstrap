<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ResetPassword;
use App\User;

class PasswordResetTest extends TestCase
{

    use DatabaseMigrations;

    private $api = '/api/v1/auth';

    /** @test */
    public function passwordEmailEndpointWithoutCredentials()
    {
        // Post to the API without an email parameter.
        $response = $this->json('POST', $this->api . '/forgot-password');
        $response->assertStatus(422)->assertJson([
            'status' => 422,
            'errors' => [
                'email' => ['The email field is required.']
            ]
        ]);
    }

    /** @test */
    public function passwordEmailEndpointWithEmail()
    {
        // Post to the API with a properly formatted email not in database.
        $response = $this->json('POST', $this->api . '/forgot-password', ['email' => 'test@example.com']);
        $response->assertStatus(422)->assertJson([
            'status' => 422,
            'errors' => [
                'email' => ['We couldn\'t find an account with that email.']
            ]
        ]);

        // Post to the API with a properly formatted email that is in database.
        $user = factory(User::class)->create();
        $response = $this->json('POST', $this->api . '/forgot-password', ['email' => $user['email']]);
        $response->assertStatus(200)->assertJson([
            'status' => 200,
            'message' => 'Email reset link sent.'
        ]);
    }

    /** @test */
    public function passwordResetEndpointWithoutCredentials()
    {
        // Post to the API without an proper credentials.
        $response = $this->json('POST', $this->api . '/password-reset');

        $response->assertStatus(422)->assertJson([
            'status' => 422,
            'errors' => [
                "token" => ["The token field is required."],
                "email" => ["The email field is required."],
                "password" => ["The password field is required."]
            ]
        ]);
    }

    /** @test */
    public function passwordResetNotificationAndReset()
    {
        // Allows us to capture the email notification.
        Notification::fake();

        // Create a user.
        $user = factory(User::class)->create();
        $token = '';

        $response = $this->json('POST', $this->api . '/forgot-password', ['email' => $user['email']]);

        // Verifies email sent and fetches token.
        Notification::assertSentTo(
            [$user],
            ResetPassword::class,
            function ($notification, $channels) use (&$token) {
                $token = $notification->token;
                return true;
            }
        );

        // Posts to password reset endpoint.
        $response = $this->postJson($this->api . '/password-reset', [
            'email' => $user->email,
            'token' => $token,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        // Assert API returns correct response.
        $response->assertStatus(200);
        $message = $response->getData()->message;
        $this->assertEquals("Password reset successful.", $message);

        // Fetch fresh $user data.
        $user = User::where('id', $user->id)->first();

        // Assert that the password *actually* was reset.
        $this->assertTrue(Hash::check('password', $user->password));
    }
}
