<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\Category\CategoryController;
use App\Http\Requests\Category\CategoryRequest;
use App\Http\Requests\Post\PostRequest;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

use Tests\TestCase;

class CategoryTest extends TestCase
{
    


    public function test_index_returns_categories_list()
    {
    
        $category = Category::factory()->count(3)->create();
        $controller = new CategoryController();
        Sanctum::actingAs(User::factory()->create());
        $response = $controller->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
    }

    /**
     * Test creating a new category.
     *
     * @return void
     */
    public function test_store_creates_new_category()
    {
        $user = User::factory()->create();
        $request = CategoryRequest::create('/api/categories', 'POST', [
            'name' => 'Test Name',
            'description' => 'Test Description',
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $controller = new CategoryController();

        Sanctum::actingAs($user);
        $response = $controller->store($request);
      
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);

        $this->assertEquals('Test Name', $responseData['name']);
        $this->assertEquals('Test Description', $responseData['description']);
        
    }



    /**
     * Test validation errors when creating a new category.
     *
     * @return void
     */
    public function test_store_validation_errors()
    {
        $user = User::factory()->create();
        $request = CategoryRequest::create('/api/categories', 'POST', [
            'name' => '', // Missing name
            'description' => '', // Missing description
            
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $controller = new CategoryController();

        Sanctum::actingAs($user);
        $response = $controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $validator = Validator::make([], (new PostRequest())->rules());
        $this->assertTrue($validator->fails());
    }


    public function test_show_returns_category()
    {
        
        $category = Category::factory()->create();
        $controller = new CategoryController();

        Sanctum::actingAs(User::factory()->create());
        $response = $controller->show($category->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
    

        $this->assertEquals($category->id, $responseData['id']);
       
    }

    public function test_update_updates_category()
    {
     
        $category = Category::factory()->create();
        $user = User::factory()->create();
        

        $request = CategoryRequest::create('/api/categories/' . $category->id, 'PUT', [
            'name' => 'Updated Test Name',
            'description' => 'Updated Test Description',
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

      

        $controller = new CategoryController();

        Sanctum::actingAs($user);
        $response = $controller->update($request, $category->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);

        $this->assertEquals('Updated Test Name', $responseData['name']);
        $this->assertEquals('Updated Test Description', $responseData['description']);
       
       
    }

    

    public function test_destroy_deletes_category()
    {
        $category = Category::factory()->create();
        $user = User::factory()->create();

        $controller = new CategoryController();

        Sanctum::actingAs($user);

        $response = $controller->destroy($category->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertEquals('Category deleted successfully', $responseData['message']);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }


   
   
}
