<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class RegisterController
{
    /**
     * Register a new user using an "All-or-Nothing" (Atomic) architecture.
     *
     * CONTEXT:
     * This method ensures strict synchronization between the server's physical hard drive
     * (the uploaded profile picture) and the database (the user record).
     *
     * ERROR HANDLING ("WHAT IF" SCENARIOS):
     *
     * 1. What if the File Upload fails?
     *    - The script throws an exception before reaching the database.
     *    - The main catch block executes. `$path` is still null, so the file-delete logic is skipped.
     *    - Result: No database record is created, no files are leaked. (Safe)
     *
     * 2. What if the Database transaction fails?
     *    - The file has already been successfully uploaded to the hard drive.
     *    - `DB::transaction` automatically undoes (rolls back) any database changes.
     *    - The main catch block executes, sees the uploaded `$path`, and immediately deletes the orphaned file.
     *    - Result: The database stays clean, and the hard drive doesn't fill up with garbage files. (Safe)
     *
     * 3. What if the immediate file deletion fails during a DB rollback? (Defense in Depth)
     *    - Sometimes, a server loses folder permissions and cannot delete the file in real-time.
     *    - A nested try-catch intercepts this secondary error so it doesn't crash the main error handler.
     *    - Result: The user still receives a clean 500 error. The orphaned file is logged as CRITICAL
     *      and will be automatically deleted later by the Scheduled Garbage Collector cron job. (Self-Healing)
     *
     * 4. What if the Welcome Email fails to send?
     *    - The email is isolated in its own secondary try-catch block (Block 2), nested inside the main success path.
     *    - The database and file are already permanently saved at this point.
     *    - The isolated catch block intercepts the email error and logs it silently for the developer.
     *      Because it is swallowed here, it prevents the main catch block from executing and wrongfully deleting the file.
     *    - Result: The user registers successfully and can use the app immediately, rather than
     *      being blocked by a slow or broken third-party email server. (Safe)
     */
    public function register(UserRequest $request): JsonResponse
    {
        $path = null;

        // ==========================================
        // BLOCK 1: CRITICAL PATH (All or Nothing)
        // ==========================================
        try {
            // 1. Validate the request
            $requestBody = $request->validated();

            // 2. Store the attachment if present
            if ($request->hasFile('attachment')) {
                $path = $request
                    ->file('attachment')
                    ->storePublicly('profiles', 'public');
            }

            // 3. Database Transaction (STRICTLY Database Logic Only)
            $user = DB::transaction(function () use ($requestBody, $path) {
                return User::create([
                    ...$requestBody,
                    'attachment' => $path,
                ]);
            });

            // ==========================================
            // BLOCK 2: SECONDARY ACTIONS (Safe to fail)
            // ==========================================
            // We only reach here if Block 1 (the database transaction) succeeded.
            try {
                // 4. Send welcome email (Queued)
                Mail::to($user->email)
                    ->send(new WelcomeMail($user->fullName()));

            } catch (Exception $mailException) {
                // Log silently. Do not crash the request.
                Log::error('Welcome email failed for User '.$user->id.': '.$mailException->getMessage());
            }

            // ==========================================
            // BLOCK 3: SUCCESS RESPONSE
            // ==========================================
            return response()->json([
                'message' => 'User created successfully',
                'data' => UserResource::make($user),
            ], 201);

        } catch (Exception $e) {
            // 5. Hard Drive Cleanup (First Line of Defense)
            if ($path && Storage::disk('public')->exists($path)) {
                try {
                    Storage::disk('public')->delete($path);
                } catch (Exception $cleanupException) {
                    // 6. Fail-Safe (Defense in Depth)
                    // If immediate deletion fails, absorb the crash so the API can finish responding.
                    // Log it so our Scheduled Garbage Collector can prune it in the background later.
                    Log::critical('Immediate file cleanup failed during DB rollback.', [
                        'path' => $path,
                        'error' => $cleanupException->getMessage(),
                    ]);
                }
            }

            // Early Return: Stop the request and inform the user cleanly.
            return response()->json([
                'message' => 'Registration failed. Changes were reverted.',
            ], 500);
        }
    }
}
