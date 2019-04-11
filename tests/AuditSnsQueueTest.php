<?php

namespace Andreshg112\LaravelAuditingNotifications\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Notification;
use Andreshg112\LaravelAuditingNotifications\AuditSnsQueue;

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
