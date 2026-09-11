<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use DB;
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
            ->paginate(perPage: $perPage, page: $page);
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
    public function update(UserUpdateRequest $request, User $user)
    {
        $user->update($request->validated());

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

    public function changePassword(ChangePasswordRequest $request)
    {
        $requestBody = $request->validated();
        $user = $request->user();

        $password = $requestBody['password'];
        $revokeCurrentSession = $request['revoke_current_session'];
        $revokeOtherSession = $request['revoke_other_session'];

        DB::transaction(function () use ($user, $password, $revokeCurrentSession, $revokeOtherSession) {
            $user->update([
                'password' => $password,
            ]);

            if ($revokeCurrentSession && $revokeOtherSession) {
                $user->tokens()->delete();
            } elseif ($revokeCurrentSession) {
                $user->currentAccessToken()->delete();
            } elseif ($revokeOtherSession) {
                $currentTokenId = $user->currentAccessToken()->id;
                $user->tokens()->where('id', '!=', $currentTokenId)->delete();
            }
        });

        $message = match (true) {
            $revokeCurrentSession && $revokeOtherSession => 'Password updated successfully. You have been logged out of all devices.',
            $revokeCurrentSession => 'Password updated successfully. You have been logged out of this session.',
            $revokeOtherSession => 'Password updated successfully. All other devices have been logged out.',
            default => 'Password updated successfully.',
        };

        // TODO: Dispatch password updated email notification here
        return response()->json([
            'message' => $message,
        ]);
    }
}
