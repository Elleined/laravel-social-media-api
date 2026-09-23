<?php

namespace App\Http\Controllers;

use App\Direction;
use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, Post $post)
    {
        Gate::authorize('view', [Comment::class, $post]);

        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $postId = $request->string('post_id');
        $status = $request->boolean('status');
        $direction = $request->enum('direction', Direction::class, Direction::ASC);
        $from = $request->date('from');
        $to = $request->date('to');

        $comments = Comment::withTrashed()
            // Soft Delete Filtering
            ->when($status === true, fn ($query) => $query->withoutTrashed())
            ->when($status === false, fn ($query) => $query->onlyTrashed())

            // Post filter
            ->when($request->filled('post_id'), fn ($query) => $query->where('post_id', $postId))

            // Date Range Filter
            ->when($request->filled('from'), fn ($query) => $query->where('created_at', '>=', $from))
            ->when($request->filled('to'), fn ($query) => $query->where('created_at', '<=', $to))

            // Sorting & Pagination
            ->when($request->filled('direction'), fn ($query) => $query->orderBy('created_at', strtolower($direction?->name)))
            ->paginate(perPage: $perPage, page: $page);

        return CommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CommentRequest $request, Post $post)
    {
        Gate::authorize('create', [Comment::class, $post]);

        $requestBody = $request->validated();

        $comment = Comment::create([
            'author_id' => $request->user()->id,
            'post_id' => $post->id,
            ...$requestBody,
        ]);

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => CommentResource::make($comment),
        ], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CommentRequest $request, Post $post, Comment $comment)
    {
        Gate::authorize('update', [Comment::class, $post, $comment]);

        $comment->update($request->validated());

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => CommentResource::make($comment),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post, Comment $comment)
    {
        Gate::authorize('delete', [Comment::class, $post, $comment]);

        $comment->delete();

        return response()->noContent();
    }
}
