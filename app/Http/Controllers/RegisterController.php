<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\FileService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;

class RegisterController
{
    public function __construct(
        private FileService $fileService
    ) {}

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
     *    - The main catch block executes. `$path` is still null, so FileService safely ignores it.
     *    - Result: No database record is created, no files are leaked. (Safe)
     *
     * 2. What if the Database transaction fails?
     *    - The file has already been successfully uploaded to the hard drive.
     *    - `DB::transaction` automatically undoes (rolls back) any database changes.
     *    - The main catch block executes and delegates cleanup to `FileService::silentDelete($path)`.
     *    - Result: The database stays clean, and the hard drive doesn't fill up with garbage files. (Safe)
     *
     * 3. What if the immediate file deletion fails during a DB rollback? (Defense in Depth)
     *    - Sometimes, a server loses folder permissions and cannot delete the file in real-time.
     *    - `FileService::silentDelete()` acts as a blast shield, intercepting the secondary error.
     *    - Result: The API responds with a clean 500 error without crashing. The orphaned file
     *      is logged as CRITICAL and will be pruned by the Garbage Collector later. (Self-Healing)
     *
     * 4. What if the Welcome Email fails to send?
     *    - The email logic is isolated in a dedicated `silentMail()` method, outside the critical path.
     *    - The database and file are already permanently saved before this method is called.
     *    - The method intercepts the email error and logs it silently for the developer.
     *    - Result: The user registers successfully and can use the app immediately, rather than
     *      being blocked by a slow or broken third-party email server. (Safe)
     */
    public function register(UserRequest $request): JsonResponse
    {
        // 1. Validate the request
        $requestBody = $request->validated();

        $path = null;
        $user = null;

        // ==========================================
        // BLOCK 1: CRITICAL PATH (All or Nothing)
        // ==========================================
        try {
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

            // 5. Send welcome email (Fire and Forget)
            $this->silentMail($user);

            // ==========================================
            // BLOCK 3: SUCCESS RESPONSE
            // ==========================================
            return response()->json([
                'message' => 'User created successfully',
                'data' => UserResource::make($user),
            ], 201);
        } catch (Exception $e) {
            // 4. File Cleanup (Defense in Depth handled by FileService)
            $this->fileService->silentDelete($path);

            return response()->json([
                'message' => 'Registration failed. Changes were reverted.',
            ], 500);
        }
    }

    /**
     * Silently attempts to send a welcome email.
     * Absorbs and logs any SMTP failures to prevent API crashes.
     */
    private function silentMail(User $user): void
    {
        if (! $user) {
            return;
        }

        try {
            Mail::to($user->email)->send(new WelcomeMail($user->fullName()));
        } catch (Exception $e) {
            Log::critical('Welcome mail send failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
