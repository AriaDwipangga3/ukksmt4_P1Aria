<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($action, $module = null, $description = null, $meta = null)
    {
        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'meta' => $meta ? json_encode($meta) : null,
            'ip_address' => Request::ip(),
        ]);
    }
}