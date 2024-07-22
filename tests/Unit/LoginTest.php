<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Validator;

class LoginTest extends TestCase
{

    /**
     * Test user can login with correct credentials.
     *
     * @return void
     */
    public function test_login_successfully()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $this->assertArrayHasKey('token', $response);
    }

    /**
     * Test login fails with incorrect password.
     *
     * @return void
     */
    public function test_login_incorrect_password()
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        $this->assertArrayHasKey('message', $response);
    }

    /**
     * Test login fails with a non-existent email.
     *
     * @return void
     */
    public function test_login_non_existent_email()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);


        $response->assertStatus(404)
            ->assertJsonFragment([
                'message' => 'User not found',
            ]);
    }

    /**
     * Test login handles validation errors.
     *
     * @return void
     */
    public function test_login_validation_errors()
    {
        $response = $this->postJson('/api/login', []);

        $validator = Validator::make([], (new LoginRequest())->rules());
        $this->assertTrue($validator->fails());

        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    /**
     * Test login handles missing email.
     *
     * @return void
     */
    public function test_login_missing_email()
    {
        $response = $this->postJson('/api/login', [
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'email' => ['The email field is required.'],
            ]);
    }

    /**
     * Test login handles missing password.
     *
     * @return void
     */
    public function test_login_missing_password()
    {
        $response = $this->postJson('/api/login', [
            'email' => 'logintest@example.com',
        ]);

        $response->assertStatus(422)
            ->assertJsonFragment([
                'password' => ['The password field is required.'],
            ]);
    }

}
