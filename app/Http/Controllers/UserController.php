<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController
{
    /**
     * Display a listing of the resource.
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
