<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function delete(?string $path, string $disk = 'public'): void
    {
        if (! $path) {
            return;
        }

        $isDeleted = false;

        try {
            $isDeleted = Storage::disk($disk)->delete($path);
        } catch (Exception $e) {
            // Hard Failure: Disk threw a catastrophic error
            throw new Exception('Catastrophic disk failure during file cleanup.'.$e->getMessage());
        }

        if (! $isDeleted) {
            // Soft Failure: Disk refused to delete it (e.g., locked file)
            throw new Exception('File deletion refused by OS. Scheduled for Garbage Collection.');
        }
    }

    public function silentDelete(?string $path, string $disk = 'public'): void
    {
        if (! $path) {
            return;
        }

        $isDeleted = false;

        try {
            $isDeleted = Storage::disk($disk)->delete($path);
        } catch (Exception $e) {
            // Hard Failure: Disk threw a catastrophic error
            Log::critical('Catastrophic disk failure during file cleanup.', [
                'path' => $path,
                'disk' => $disk,
                'error' => $e->getMessage(),
            ]);
        }

        if (! $isDeleted) {
            // Soft Failure: Disk refused to delete it (e.g., locked file)
            Log::critical('File deletion refused by OS. Scheduled for Garbage Collection.', [
                'path' => $path,
                'disk' => $disk,
            ]);
        }
    }
}
