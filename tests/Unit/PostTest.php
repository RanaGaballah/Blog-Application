<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\Post\PostController;
use App\Http\Requests\Post\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use App\Services\LoggingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;
use Mockery;
use Tests\TestCase;

class PostTest extends TestCase
{



    protected $loggingServiceMock;

    public function setUp(): void
    {
        parent::setUp();
        $this->loggingServiceMock = Mockery::mock(LoggingService::class);
    }

    /**
     * Test display posts.
     *
     * @return void
     */

    public function test_index_returns_posts_list()
    {
    
        $posts = Post::factory()->count(3)->create();
        $controller = new PostController($this->loggingServiceMock);
        Sanctum::actingAs(User::factory()->create());
        $response = $controller->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
    }

    /**
     * Test creating a new post.
     *
     * @return void
     */
    public function test_store_creates_new_post()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $request = PostRequest::create('/api/posts', 'POST', [
            'title' => 'Test Title',
            'body' => 'Test Body',
            'category_id' => $category->id,
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->loggingServiceMock->shouldReceive('log')->once();

        $controller = new PostController($this->loggingServiceMock);

        Sanctum::actingAs($user);

       
        $response = $controller->store($request);
      
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        $responseData = $response->getData(true);

        $this->assertEquals('Test Title', $responseData['title']);
        $this->assertEquals('Test Body', $responseData['body']);
        $this->assertEquals($category->id, $responseData['category']['id']);
        $this->assertEquals($category->name, $responseData['category']['name']);
        $this->assertEquals($category->description, $responseData['category']['description']);
    }



    /**
     * Test validation errors when creating a new post.
     *
     * @return void
     */
    public function test_store_validation_errors()
    {
        $user = User::factory()->create();
        $request = PostRequest::create('/api/posts', 'POST', [
            'title' => '', // Missing title
            'body' => '', // Missing body
            'category_id' => null, // Missing category_id
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $controller = new PostController(new LoggingService());

        Sanctum::actingAs($user);
        $response = $controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);

        $validator = Validator::make([], (new PostRequest())->rules());
        $this->assertTrue($validator->fails());
    }

    /**
     * Test show specific post.
     *
     * @return void
     */
    public function test_show_returns_post()
    {
        $post = Post::factory()->create();
        $controller = new PostController($this->loggingServiceMock);

        Sanctum::actingAs(User::factory()->create());

        $response = $controller->show($post->id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
    

        $this->assertEquals($post->id, $responseData['id']);
       
    }


    /**
     * Test updating a post.
     *
     * @return void
     */

    public function test_update_updates_post()
    {

        $post = Post::factory()->create();
        $user = User::factory()->create();
        $category = Category::factory()->create();

        $request = PostRequest::create('/api/posts/' . $post->id, 'PUT', [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
            'category_id' => $category->id,
        ]);

        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        $this->loggingServiceMock->shouldReceive('log')->once();

        $controller = new PostController($this->loggingServiceMock);

        Sanctum::actingAs($user);
        $response = $controller->update($request, $post->id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);

        $this->assertEquals('Updated Title', $responseData['title']);
        $this->assertEquals('Updated Body', $responseData['body']);
       
       
    }

    /**
     * Test deleting a post.
     *
     * @return void
     */

    public function test_destroy_deletes_post()
    {
    
        $post = Post::factory()->create();
        $user = User::factory()->create();

        $controller = new PostController($this->loggingServiceMock);

        $this->loggingServiceMock->shouldReceive('log')->once();

        Sanctum::actingAs($user);
        $response = $controller->destroy($post->id);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $responseData = $response->getData(true);
        $this->assertEquals('Post deleted successfully', $responseData['message']);
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    // Close mockery expectations
    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
