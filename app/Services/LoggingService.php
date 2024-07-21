<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class LoggingService
{


    public function Log(?Request $request = null, Post $post, $logName)
    {
        try {

            $commonLogData = [
                'title' => $post->title,
                'body' => $post->body,
                'post_id' => $post->id,
                'user_id' => $request ? $request->user()->id : $post->user_id,
                'category_id' => $post->category_id,
            ];

            $logData = match ($logName) {
                'Post Created' => array_merge($commonLogData, [
                    'username' => $request->user()->name,
                    'created_at' => $post->created_at,
                ]),
                'Post Updated' => array_merge($commonLogData, [
                    'username' => $request->user()->name,
                    'updated_at' => $post->updated_at,
                ]),
                default => array_merge($commonLogData, [
                    'deleted_at' => now(),
                ]),
            };

            Log::channel('post_actions')->info($logName, $logData);
            
        } catch (\Exception $e) {
            $response = [
                'message' => 'Logging posts failed',
                'errors' => $e->getMessage(),
            ];
            return response()->json($response, 500);
        }
    }

}
