<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use DB;
use Gate;
use Illuminate\Http\Request;
use Mail;

class AdminController
{
    public function index(Request $request)
    {
        Gate::authorize('index', [User::class]);

        $page = $request->integer('page', 1);
        $perPage = $request->integer('per_page', 10);
        $status = $request->boolean('status');
        $search = $request->string('search');

        $users = User::withTrashed()
            ->when($status === true, fn ($query) => $query->withoutTrashed())
            ->when($status === false, fn ($query) => $query->onlyTrashed())
            ->when($request->filled('search'), function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('first_name', 'like', "{$search}%")
                        ->orWhere('last_name', 'like', "{$search}%");
                });

            })
            ->paginate(perPage: $perPage, page: $page);

        return UserResource::collection($users);
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('create', [User::class]);

        $requestBody = $request->validated();

        $user = User::create([
            ...$requestBody,
            'created_by' => $request->user()->id, // Current is admin user
        ]);

        Mail::to($user->email)
            ->send(new WelcomeMail($user->fullName()));

        return response()->json([
            'message' => 'User created successfully',
            'data' => UserResource::make($user),
        ], 201);
    }

    public function show(User $user)
    {
        Gate::authorize('view', [User::class, $user]);

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $user)
    {
        Gate::authorize('update', [User::class, $user]);

        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'data' => UserResource::make($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        Gate::authorize('delete', [User::class, $user]);

        DB::transaction(function () use ($user) {
            $user->delete();

            $user->tokens()->delete();
        });

        return response()->noContent();
    }
}
