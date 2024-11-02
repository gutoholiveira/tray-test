<?php

namespace App\Contracts\Services\User;

use App\Models\User;

interface IUserService
{
    /**
     * Send an email with the sales data.
     *
     * @param User $user
     * @return void
     */
    public function sendMail(User $user): void;

    /**
     * Routine to send an email to each user
     *
     * @return void
     */
    public function sendDailyReportRoutine(): void;
}
