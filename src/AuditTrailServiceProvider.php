<?php

namespace Abdelrhman\AuditTrail;

use Illuminate\Support\ServiceProvider;
use Abdelrhman\AuditTrail\Commands\AuditTrailListCommand;

class AuditTrailServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // تحميل migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // نشر ملفات الإعداد
        $this->publishes([
            __DIR__.'/../config/audittrail.php' => config_path('audittrail.php'),
        ], 'config');

        // تسجيل الأوامر
        if ($this->app->runningInConsole()) {
            $this->commands([
                AuditTrailListCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/audittrail.php', 'audittrail');
    }
}
