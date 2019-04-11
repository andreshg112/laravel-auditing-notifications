<?php

namespace Andreshg112\LaravelAuditingNotifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Use this notification if you want to queue the delivery of the SNS message.
 */
class AuditSnsQueue extends AuditSns implements ShouldQueue
{
    use Queueable;
}
