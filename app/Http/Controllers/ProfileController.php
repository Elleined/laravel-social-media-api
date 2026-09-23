<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use DB;
use Gate;
use Illuminate\Http\Request;

class ProfileController
{
    public function show(Request $request)
    {
        Gate::authorize('me', [User::class]);

        $user = $request->user();

        return UserResource::make($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request)
    {
        Gate::authorize('me', [User::class]);

        $user = $request->user();

        $user->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'data' => UserResource::make($user),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Gate::authorize('me', [User::class]);

        $user = $request->user();

        DB::transaction(function () use ($user) {
            $user->delete();

            $user->tokens()->delete();
        });

        return response()->noContent();
    }

    public function password(ChangePasswordRequest $request)
    {
        Gate::authorize('me', [User::class]);

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
