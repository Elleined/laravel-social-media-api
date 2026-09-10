<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class UserController
{
    /**
     * Display a paginated listing of users with conditional status and grouped search filters.
     *
     * QUERY LOGIC & ARCHITECTURE NOTES:
     * --------------------------------------------------------------------------------------
     * 1. Top-Level Filters & AND Precedence:
     *    In Eloquent, chained `when()` and `where()` methods automatically join with SQL `AND`.
     *    `User::withTrashed()` initializes the base set, which is then narrowed by `$status`.
     *
     * 2. Encapsulated Search ($sub Closure):
     *    CRITICAL: The inner `$q->where(function ($sub) ...)` encapsulates multi-column `OR`
     *    conditions into parentheses: `AND (first_name LIKE ... OR last_name LIKE ...)`.
     *    Without this `$sub` closure, root-level `orWhere` calls break SQL operator precedence
     *    and leak soft-deleted records even when `$status === true`.
     *
     * 3. Performance & Wildcards:
     *    Uses prefix wildcard search (`"{$search}%"`). This allows B-Tree index scans on
     *    `first_name` and `last_name` columns instead of forcing full-table scans.
     *
     * @return LengthAwarePaginator
     */
    public function index(Request $request)
    {
        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $status = $request->filled('status') ? $request->boolean('status') : null;
        $search = $request->string('search');

        return User::withTrashed()
            ->when($status === true, fn ($query) => $query->withoutTrashed())
            ->when($status === false, fn ($query) => $query->onlyTrashed())
            ->when($request->filled('search'), function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "{$search}%")
                        ->orWhere('last_name', 'like', "{$search}%");
                });

            })
            ->paginate($perPage, ['*'], 'page', $page);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $userRequest)
    {
        $user = User::create($userRequest->validated());

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $userUpdateRequest, User $user)
    {
        $user->update($userUpdateRequest->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
