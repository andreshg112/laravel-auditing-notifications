<?php

namespace Andreshg112\LaravelAuditingNotifications\Tests;

use Andreshg112\LaravelAuditingNotifications\AuditSns;
use Illuminate\Support\Facades\Config;
use Lab123\AwsSns\Messages\AwsSnsMessage;
use Orchestra\Testbench\TestCase;

class AuditSnsTest extends TestCase
{
    /** @test */
    public function it_returns_a_sns_message()
    {
        /*
         * You should not mock the Request facade. Instead, pass the input you desire into the HTTP
         * helper methods such as get and post when running your test. Likewise, instead of mocking
         * the Config facade, call the Config::set method in your tests.
         *
         * https://laravel.com/docs/5.6/mocking#mocking-facades
         */
        Config::set('audit.notification-driver.sns.topic_arn', 'topic:arn');

        $model = new AuditableModel();

        $model->setAuditEvent('created');

        $notification = new AuditSns($model->toAudit());

        $snsMessage = $notification->toAwsSnsTopic($model);

        $this->assertInstanceOf(AwsSnsMessage::class, $snsMessage);
    }
}
