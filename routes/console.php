<?php

use App\Services\SellerService;
use App\Services\UserService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
    (new SellerService())->sendDailyReportRoutine();
    (new UserService())->sendDailyReportRoutine();
})->dailyAt('20:00');