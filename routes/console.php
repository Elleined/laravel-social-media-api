<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

// Prune expired password reset tokens
Schedule::call(function () {
    DB::table('password_reset_tokens')
        ->where('expires_at', '<=', Carbon::now())
        ->delete();
})->daily();

// Prune expired Sanctum personal access tokens
Schedule::command('sanctum:prune-expired --hours=24')->daily();
