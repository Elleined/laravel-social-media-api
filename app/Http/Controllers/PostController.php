<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\PostParams;
use DB;

class PostController
{
    public function feed()
    {
        //
    }

    public function me()
    {
        //
    }

    private function index(PostParams $params)
    {
        $page = $params->page;
        $perPage = $params->perPage;
        $authorId = $params?->authorId;
        $titleSearch = $params?->titleSearch;
        $status = $params?->status;
        $direction = $params?->direction;
        $from = $params?->from;
        $to = $params?->to;

        DB::table('posts as post')
            ->when(! empty($authorId), fn ($query) => $query->where('author_id', '=', $authorId))
            ->when(! empty($titleSearch), fn ($query) => $query->where('title', 'like', "{$titleSearch}%"))
            ->select(['user.id'])
            ->paginate(perPage: $perPage, page: $page);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        $requestBody = $request->validated();

        $post = Post::query()->create([
            'author_id' => $request->user()->id,
            ...$requestBody,
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'data' => PostResource::make($post),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateRequest $request, Post $post)
    {
        $post->update($request->validated());

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => PostResource::make($post),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }
}
