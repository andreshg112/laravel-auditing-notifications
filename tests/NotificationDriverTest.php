<?php

namespace Andreshg112\LaravelAuditingNotifications\Tests;

use Orchestra\Testbench\TestCase;
use OwenIt\Auditing\Models\Audit;
use Andreshg112\LaravelAuditingNotifications\AuditSns;
use Andreshg112\LaravelAuditingNotifications\NotificationDriver;

class NotificationDriverTest extends TestCase
{
    /** @test */
    public function it_can_audit()
    {
        $model = new AuditableModel;

        $model->setAuditEvent('created');

        $this->expectsNotification($model, AuditSns::class);

        $notificationDriver = new NotificationDriver;

        $audit = $notificationDriver->audit($model);

        $this->assertInstanceOf(Audit::class, $audit);
    }

    /** @test */
    public function it_cannot_prune()
    {
        $model = new AuditableModel;

        $notificationDriver = new NotificationDriver;

        $prune = $notificationDriver->prune($model);

        $this->assertFalse($prune);
    }
}

class AuditableModel extends \Illuminate\Database\Eloquent\Model implements \OwenIt\Auditing\Contracts\Auditable
{
    use \OwenIt\Auditing\Auditable, \Illuminate\Notifications\Notifiable;

    // Overwrite methods just for testing purposes.

    public function readyForAuditing(): bool
    {
        return true;
    }

    protected function resolveIpAddress(): string
    {
        return '';
    }

    protected function resolveUrl(): string
    {
        return '';
    }

    protected function resolveUser()
    {
    }

    protected function resolveUserAgent()
    {
    }
}
