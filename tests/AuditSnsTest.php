<?php

namespace Andreshg112\LaravelAuditingNotifications\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use Lab123\AwsSns\Messages\AwsSnsMessage;
use Andreshg112\LaravelAuditingNotifications\AuditSns;

class AuditSnsTest extends TestCase
{
    /** @test */
    public function it_returns_a_sns_message()
    {
        $this->mockConfig();

        $model = new AuditableModel;

        $model->setAuditEvent('created');

        $notification = new AuditSns($model->toAudit());

        $snsMessage = $notification->toAwsSnsTopic($model);

        $this->assertInstanceOf(AwsSnsMessage::class, $snsMessage);
    }

    public function mockConfig()
    {
        $events = ['created', 'updated', 'deleted', 'restored'];

        Config::shouldReceive('get')
            ->with('audit.events', $events)
            ->andReturn($events);

        Config::shouldReceive('get')
            ->with('audit.notification-driver.sns.topic_arn')
            ->once()
            ->andReturn('topic:arn');

        Config::shouldReceive('get')
            ->with('audit.console', false)
            ->once()
            ->andReturn(false);

        Config::shouldReceive('get')
            ->with('audit.strict', false)
            ->once()
            ->andReturn(false);

        Config::shouldReceive('get')
            ->with('audit.timestamps', false)
            ->once()
            ->andReturn(false);

        Config::shouldReceive('get')
            ->with('audit.user.morph_prefix', 'user')
            ->once()
            ->andReturn('user');
    }
}
