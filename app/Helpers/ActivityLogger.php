<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(
        string $activity,
        string $description,
        ?string $module = null,
        ?string $action = null
    ): void {
        ActivityLog::create([
            'user_id' => Auth::id(),

            'activity' => $activity,

            'description' => $description,

            'module' => $module,

            'action' => $action,

            'method' => request()->method(),

            'ip_address' => request()->ip(),

            'user_agent' => request()->userAgent(),
        ]);
    }
}