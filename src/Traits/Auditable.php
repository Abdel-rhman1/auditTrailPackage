<?php

namespace Abdelrhman\AuditTrail\Traits;

use Abdelrhman\AuditTrail\Models\AuditTrail;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            $model->recordAudit('created');
        });

        static::updated(function ($model) {
            $model->recordAudit('updated');
        });

        static::deleted(function ($model) {
            $model->recordAudit('deleted');
        });
    }

    protected function recordAudit($event)
    {
        $userId = config('audittrail.track_user') ? Auth::id() : null;

        AuditTrail::create([
            'user_id' => $userId,
            'event' => $event,
            'auditable_type' => get_class($this),
            'auditable_id' => $this->id,
            'old_values' => $event === 'updated' ? $this->getOriginal() : null,
            'new_values' => $event !== 'deleted' ? $this->getAttributes() : null,
        ]);
    }
}
