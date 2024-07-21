<?php

namespace App\Http\Controllers\Api\Post;

use App\Http\Controllers\Controller;
use App\Http\Requests\Post\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
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
        Log::channel('post_actions')->info('Post created', [
            'username' =>$request->user()->name,
            'title' => $post->title,
            'body' => $post->body,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'category_id' => $post->category_id,
            'created_at' => $post->created_at,
        ]);

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
        Log::channel('post_actions')->info('Post updated', [
            'username' =>$request->user()->name,
            'title' => $post->title,
            'body' => $post->body,
            'post_id' => $post->id,
            'user_id' => $request->user()->id,
            'category_id' => $post->category_id,
            'updated_at' => $post->updated_at,
        ]);

        return response()->json(new PostResource($post), 200);
    }

    /**
     * Remove the specified post from database.
     */
    public function destroy($id): JsonResponse
    {
        $post = Post::findOrFail($id);
        $post->delete();

        Log::channel('post_actions')->info('Post deleted', [
            'title' => $post->title,
            'body' => $post->body,
            'post_id' => $post->id,
            'user_id' => $post->user_id,
            'category_id' => $post->category_id,
            'deleted_at' => now(),
        ]);
        return response()->json(['message' => 'Post deleted successfully']);
    }
}
