<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Audit
{
    /**
     * Forbidden keys that must never be recorded in audit details.
     */
    protected const SECRET_KEYS = [
        'password',
        'password_confirmation',
        'otp',
        'otp_code',
        'code',
        'token',
        'remember_token',
        'api_token',
        'secret',
        'key',
    ];

    /**
     * Record an audit log entry.
     *
     * @param string $action e.g. 'question.approved', 'role.changed'
     * @param mixed $target Model instance, class string, or name
     * @param array $detail Key-value array of change details (old/new, metadata)
     * @param int|null $userId Optional actor ID, defaults to auth()->id()
     * @return AuditLog
     */
    public static function log(string $action, mixed $target = null, array $detail = [], ?int $userId = null): AuditLog
    {
        $targetType = null;
        $targetId = null;

        if ($target instanceof Model) {
            $targetType = class_basename($target);
            $targetId = (string) $target->getKey();
        } elseif (is_string($target)) {
            $targetType = class_basename($target);
        }

        $sanitizedDetail = static::sanitize($detail);

        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'detail' => $sanitizedDetail,
            'created_at' => now(),
        ]);
    }

    /**
     * Recursively scrub any passwords, OTPs, or sensitive keys from log detail.
     */
    protected static function sanitize(array $data): array
    {
        $cleaned = [];
        foreach ($data as $key => $value) {
            $lowerKey = strtolower((string) $key);

            if (in_array($lowerKey, self::SECRET_KEYS, true) || str_contains($lowerKey, 'password') || str_contains($lowerKey, 'secret')) {
                $cleaned[$key] = '[REDACTED]';
                continue;
            }

            if (is_array($value)) {
                $cleaned[$key] = static::sanitize($value);
            } else {
                $cleaned[$key] = $value;
            }
        }

        return $cleaned;
    }
}
