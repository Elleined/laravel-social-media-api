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

        [
            'password' => $password,
            'revoke_current_session' => $revokeCurrentSession,
            'revoke_other_session' => $revokeOtherSession
        ] = $request->validated();

        $user = $request->user();

        $message = DB::transaction(function () use ($user, $password, $revokeCurrentSession, $revokeOtherSession) {
            // 1. Update the password securely (ensure it is hashed)
            $user->update([
                'password' => $password,
            ]);

            // 2. Handle token revocation combinations
            if ($revokeCurrentSession && $revokeOtherSession) {
                $user->tokens()->delete();

                return 'Password updated successfully. You have been logged out of all devices.';
            }

            if ($revokeCurrentSession) {
                $user->currentAccessToken()?->delete();

                return 'Password updated successfully. You have been logged out of this session.';
            }

            if ($revokeOtherSession) {
                $currentTokenId = $user->currentAccessToken()?->id;

                $user->tokens()
                    ->when($currentTokenId, fn ($query) => $query->where('id', '!=', $currentTokenId))
                    ->delete();

                return 'Password updated successfully. All other devices have been logged out.';
            }

            return 'Password updated successfully.';
        });

        // TODO: Dispatch password updated email notification here
        return response()->json([
            'message' => $message,
        ]);
    }
}
