<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class QueuedResetPassword extends ResetPassword implements ShouldQueue, ShouldBeUnique
{
    use Queueable;

    public $tries = 3;

    public $backoff = 2;
}
