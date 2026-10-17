<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schedule;

// The public demo starts from a clean slate every night
Schedule::command('demo:reset --force')->dailyAt('04:00')->when(fn (): bool => (bool) config('demo.reset_nightly'));
