<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Post\StorePostRequest;
use App\Http\Requests\Post\UpdatePostRequest;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;

/**
 *
 */
class PostApiController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Post::query()->paginate();
    }

    /**
     * @param StorePostRequest $request
     * @param PostService      $postService
     * @return JsonResponse
     */
    public function store(StorePostRequest $request, PostService $postService): JsonResponse
    {
        $postData = $request->validated();
        $postData['author_id'] = auth()->user()->getAuthIdentifier();

        $post = $postService->store($postData);

        return response()->json($post->toArray(), 201);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function show(Post $post): JsonResponse
    {
        if (auth()->user()->cannot('view', $post)) {
            abort(403);
        }

        return response()->json($post->toArray());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): JsonResponse
    {
        if (auth()->user()->cannot('update', $post)) {
            abort(403);
        }
        $post->update($request->validated());

        return response()->json($post);
    }

    /**
     * @param Post $post
     * @return JsonResponse
     */
    public function destroy(Post $post): JsonResponse
    {
        if (auth()->user()->cannot('delete', $post)) {
            abort(403);
        }
        $post->delete();
        return response()->json();
    }
}
