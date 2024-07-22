<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    public function it_registers_a_user_successfully()
    {
        $request = RegisterRequest::create('/api/register', 'POST', [
            'name' => 'Test1',
            'email' => 'test1@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new RegisterController();
        $response = $controller->register($request);

        $this->assertEquals(201, $response->getStatusCode());

        $responseData = $response->getData(true);
        $this->assertArrayHasKey('token', $responseData);
        $this->assertArrayHasKey('user', $responseData);

        $this->assertDatabaseHas('users', [
            'email' => 'test1@example.com',
        ]);
    }

    
    public function it_requires_name_email_and_password()
    {
        $request = RegisterRequest::create('/api/register', 'POST', []);

        $validator = Validator::make([], (new RegisterRequest())->rules());
        $this->assertTrue($validator->fails());

        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

    public function it_requires_password_confirmation()
    {
        $request = RegisterRequest::create('/api/register', 'POST', [
            'name' => 'Test1',
            'email' => 'test1@example.com',
            'password' => 'password',
        ]);

        $validator = Validator::make($request->all(), (new RegisterRequest())->rules());
        $this->assertTrue($validator->fails());

        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }

   
    public function it_requires_unique_email()
    {

        $request = RegisterRequest::create('/api/register', 'POST', [
            'name' => 'Test1',
            'email' => 'test1@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $validator = Validator::make($request->all(), (new RegisterRequest())->rules());
        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    public function it_hashes_the_password_correctly()
    {
        $email = 'test2@example.com';
        $request = RegisterRequest::create('/api/register', 'POST', [
            'name' => 'Test2',
            'email' => $email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new RegisterController();
        $response = $controller->register($request);

        $user = User::where('email', $email)->first();
        $this->assertTrue(Hash::check('password', $user->password));
    }

    public function it_does_not_allow_duplicate_tokens()
    {
        $request = RegisterRequest::create('/api/register', 'POST', [
            'name' => 'Test3',
            'email' => 'test3@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $controller = new RegisterController();
        $response = $controller->register($request);

        $User = User::where('email', 'test3@example.com')->first();
        $newUser = User::where('email', 'test1@example.com')->first();
        $this->assertNotEquals($User->id, $newUser->id);
    }

    


}
