<?php

namespace App\Http\Controllers;

use App\Direction;
use App\Http\Requests\PostRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController
{
    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $authorId = $request->filled('author_id') ? $request->string('author_id') : null;
        $search = $request->filled('search') ? $request->string('title') : null;
        $status = $request->filled('status') ? $request->boolean('status') : null;
        $direction = $request->filled('direction') ? $request->enum('direction', Direction::class, Direction::ASC) : null;
        $from = $request->filled('from') ? $request->date('from') : null;
        $to = $request->filled('to') ? $request->date('to') : null;

        $posts = Post::withTrashed()
            // Soft Delete Filtering
            ->when($status === true, fn ($query) => $query->withoutTrashed())
            ->when($status === false, fn ($query) => $query->onlyTrashed())

            // Filters
            ->when(! empty($authorId), fn ($query) => $query->where('author_id', $authorId))
            ->when(! empty($search), fn ($query) => $query->where('title', 'like', "{$search}%"))

            // Date Range Filter
            ->when(! is_null($from), fn ($query) => $query->where('created_at', '>=', $from))
            ->when(! is_null($to), fn ($query) => $query->where('created_at', '<=', $to))

            // Sorting & Pagination
            ->when(! is_null($direction), fn ($query) => $query->orderBy('created_at', strtolower($direction?->name)))
            ->paginate(perPage: $perPage, page: $page);

        return PostResource::collection($posts);
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
