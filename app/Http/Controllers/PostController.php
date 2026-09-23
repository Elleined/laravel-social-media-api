<?php

namespace App\Http\Controllers;

use App\Direction;
use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Gate;
use Illuminate\Http\Request;

class PostController
{
    public function index(Request $request)
    {
        Gate::authorize('view', [Post::class]);

        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $authorId = $request->string('author_id');
        $search = $request->string('title');
        $status = $request->boolean('status');
        $direction = $request->enum('direction', Direction::class, Direction::ASC);
        $from = $request->date('from');
        $to = $request->date('to');

        $posts = Post::withTrashed()
            // Soft Delete Filtering
            ->when($status === true, fn ($query) => $query->withoutTrashed())
            ->when($status === false, fn ($query) => $query->onlyTrashed())

            // Filters
            ->when($request->filled('author_id'), fn ($query) => $query->where('author_id', $authorId))
            ->when($request->filled('search'), fn ($query) => $query->where('title', 'like', "{$search}%"))

            // Date Range Filter
            ->when($request->filled('from'), fn ($query) => $query->where('created_at', '>=', $from))
            ->when($request->filled('to'), fn ($query) => $query->where('created_at', '<=', $to))

            // Sorting & Pagination
            ->when($request->filled('direction'), fn ($query) => $query->orderBy('created_at', strtolower($direction?->name)))
            ->paginate(perPage: $perPage, page: $page);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostRequest $request)
    {
        Gate::authorize('create', [Post::class]);

        $requestBody = $request->validated();

        $post = Post::create([
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
    public function update(PostRequest $request, Post $post)
    {
        Gate::authorize('update', [Post::class, $post]);

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
        Gate::authorize('delete', [Post::class, $post]);

        $post->delete();

        return response()->noContent();
    }
}
