<?php

use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Prune expired password reset tokens
Schedule::call(function () {
    DB::table('password_reset_tokens')
        ->where('expires_at', '<=', Carbon::now())
        ->delete();
})->daily();

// Prune expired Sanctum personal access tokens
Schedule::command('sanctum:prune-expired --hours=24')->daily();
