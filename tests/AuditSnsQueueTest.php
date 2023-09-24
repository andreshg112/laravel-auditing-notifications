<?php

namespace Andreshg112\LaravelAuditingNotifications\Tests;

use Andreshg112\LaravelAuditingNotifications\AuditSnsQueue;
use Illuminate\Support\Facades\Notification;
use Orchestra\Testbench\TestCase;

class AuditSnsQueueTest extends TestCase
{
    /** @test */
    public function it_sends_the_notification()
    {
        Notification::fake();

        $model = new AuditableModel;

        $model->setAuditEvent('created');

        $model->notify(new AuditSnsQueue($model->toAudit()));

        Notification::assertSentTo($model, AuditSnsQueue::class);
    }
}
