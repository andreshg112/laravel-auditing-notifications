<?php

namespace Andreshg112\LaravelAuditingNotifications;

use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Arr;
use OwenIt\Auditing\Contracts\Audit;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\AuditDriver;
use OwenIt\Auditing\Exceptions\AuditingException;

class NotificationDriver implements AuditDriver
{
    /**
     * Perform an audit.
     *
     * @param  \OwenIt\Auditing\Contracts\Auditable|\Illuminate\Notifications\Notifiable  $model
     * @return \OwenIt\Auditing\Contracts\Audit
     */
    public function audit(Auditable $model): Audit
    {
        throw_unless(
            in_array(Notifiable::class, class_uses_recursive($model)),
            new AuditingException(
                "The model doesn't use the \Illuminate\Notifications\Notifiable trait."
            )
        );

        $audit = new \OwenIt\Auditing\Models\Audit($model->toAudit());

        /** @var string[] $auditEventClasses */
        $auditEventClasses = Arr::wrap(
            config('audit.notification-driver.notifications', AuditSns::class)
        );

        foreach ($auditEventClasses as $auditEventClass) {
            /** @var \Illuminate\Notifications\Notification|AuditSns $notification */
            $notification = new $auditEventClass($audit->toArray());

            $model->notify($notification);
        }

        return $audit;
    }

    /**
     * Remove older audits that go over the threshold.
     *
     * @param  \OwenIt\Auditing\Contracts\Auditable  $model
     * @return bool
     */
    public function prune(Auditable $model): bool
    {
        return false;
    }
}
