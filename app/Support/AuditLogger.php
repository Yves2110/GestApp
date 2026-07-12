<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Enregistre une entrée d'audit.
     */
    public static function log(string $action, ?string $description = null, ?string $subjectType = null, $subjectId = null): void
    {
        try {
            AuditLog::create([
                'user_id'      => Auth::id(),
                'action'       => $action,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => $subjectId,
                'method'       => Request::method(),
                'url'          => Request::fullUrl(),
                'ip_address'   => Request::ip(),
                'user_agent'   => substr((string) Request::userAgent(), 0, 500),
            ]);
        } catch (\Throwable $e) {
            // L'audit ne doit jamais casser l'application.
        }
    }
}
