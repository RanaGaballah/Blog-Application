<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Services\LoggingService;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    /**
     * Log entries
     * for auditing purposes.
     */
    protected $loggingService;

    public function __construct(LoggingService $loggingService)
    {
        $this->loggingService = $loggingService;
    }

    /**
     * Display a listing of posts.
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['user', 'category'])->get();
        return response()->json(PostResource::collection($posts));
    }

    /**
     * Create a new post
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = Post::create([
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'user_id' => $request->user()->id,
            'category_id' => $request->input('category_id'),
        ]);

        $post->load(['category']);
        $this->loggingService->log($request, $post, 'Post Created');

        return response()->json(new PostResource($post), 201);
    }


    /**
     * Display the specified post.
     */
    public function show($id): JsonResponse
    {
        $post = Post::with(['user', 'category'])->findOrFail($id);
        return response()->json(new PostResource($post));
    }


    /**
     * Update the specified post.
     */
    public function update(PostRequest $request, $id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->update($request->only(['title', 'body', 'category_id']));

        $post->load(['category']);
        $this->loggingService->log($request, $post, 'Post Updated');

        return response()->json(new PostResource($post), 200);
    }

    /**
     * Remove the specified post from database.
     */
    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();
        $this->loggingService->log(null, $post, 'Post Deleted');
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
