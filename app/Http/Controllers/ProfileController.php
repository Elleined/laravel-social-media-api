<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use DB;
use Illuminate\Http\Request;

class ProfileController
{
    public function show(Request $request)
    {
        $currentUser = $request->user();

        return UserResource::make($currentUser);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request)
    {
        $currentUser = $request->user();

        $currentUser->update($request->validated());

        return response()->json([
            'message' => 'User updated successfully',
            'data' => UserResource::make($currentUser),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $currentUser = $request->user();

        DB::transaction(function () use ($currentUser) {
            $currentUser->delete();

            $currentUser->tokens()->delete();
        });

        return response()->noContent();
    }

    public function password(ChangePasswordRequest $request)
    {
        $requestBody = $request->validated();

        $currentUser = $request->user();

        $password = $requestBody['password'];
        $revokeCurrentSession = $request['revoke_current_session'];
        $revokeOtherSession = $request['revoke_other_session'];

        DB::transaction(function () use ($currentUser, $password, $revokeCurrentSession, $revokeOtherSession) {
            $currentUser->update([
                'password' => $password,
            ]);

            if ($revokeCurrentSession && $revokeOtherSession) {
                $currentUser->tokens()->delete();
            } elseif ($revokeCurrentSession) {
                $currentUser->currentAccessToken()->delete();
            } elseif ($revokeOtherSession) {
                $currentTokenId = $currentUser->currentAccessToken()->id;
                $currentUser->tokens()->where('id', '!=', $currentTokenId)->delete();
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
