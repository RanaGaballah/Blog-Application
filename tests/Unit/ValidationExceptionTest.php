<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ValidationExceptionTest extends TestCase
{
  
    public function test_returns_method_not_allowed_response()
    {
        $response = $this->putJson('/api/posts/1');

        $response->assertStatus(500)
                 ->assertJson([
                     'status' => 500,
                     'message' => 'An unexpected error occurred.',
                     'details' => 'Unauthenticated.'
                 ]);
    }

    public function test_returns_not_found_response()
    {
        $response = $this->getJson('/api/non-existing-route');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'Route not found',
                     
                 ]);
    }

    public function test_returns_model_not_found_response()
    {
        $user = User::factory()->create();
        
        Sanctum::actingAs($user);
        
        $response = $this->getJson('/api/posts/9999');

        $response->assertStatus(404)
                 ->assertJson([
                     'status' => 404,
                     'message' => 'Post not found',
                    
                 ]);
    }
}
