<?php

namespace Andreshg112\LaravelAuditingNotifications;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;
use Lab123\AwsSns\Channels\AwsSnsTopicChannel;
use Lab123\AwsSns\Messages\AwsSnsMessage;
use OwenIt\Auditing\Exceptions\AuditingException;

class AuditSns extends Notification
{
    /** @var array */
    protected $auditData = null;

    /**
     * Create a new notification instance.
     *
     * @param  array  $auditData
     */
    public function __construct(array $auditData)
    {
        $this->auditData = $auditData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  \OwenIt\Auditing\Contracts\Auditable  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [AwsSnsTopicChannel::class];
    }

    /**
     * Get the SNS representation of the notification.
     *
     * @param  \OwenIt\Auditing\Contracts\Auditable  $notifiable
     * @return \Lab123\AwsSns\Messages\AwsSnsMessage
     */
    public function toAwsSnsTopic($notifiable)
    {
        $config = 'audit.notification-driver.sns.topic_arn';

        $topicArn = Config::get($config);

        throw_if(
            empty($topicArn),
            new AuditingException("config $config is not set.")
        );

        return (new AwsSnsMessage())
            ->message(json_encode($this->auditData))
            ->topicArn($topicArn);
    }
}
