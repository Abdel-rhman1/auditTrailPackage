<?php

namespace Abdelrhman\AuditTrail\Commands;

use Illuminate\Console\Command;
use Abdelrhman\AuditTrail\Models\AuditTrail;

class AuditTrailListCommand extends Command
{
    protected $signature = 'audit:list {count=10}';
    protected $description = 'Show recent audit trails';

    public function handle()
    {
        $count = $this->argument('count');
        $audits = AuditTrail::latest()->take($count)->get();

        if ($audits->isEmpty()) {
            $this->info('No audit trails found.');
            return;
        }

        $this->table(
            ['ID', 'User ID', 'Event', 'Model', 'Model ID', 'Created At'],
            $audits->map(fn ($a) => [
                $a->id, $a->user_id, $a->event, class_basename($a->auditable_type), $a->auditable_id, $a->created_at
            ])
        );
    }
}
