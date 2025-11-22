<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function can(string $permission): bool
    {
        $user = Auth::user();
        if (!$user || !$user->is_active) {
            return false;
        }
        if ($user->role === 'director') {
            return true;
        }
        $role = $user->roleInfo;
        if (!$role || !$role->permissions) {
            return false;
        }
        return $role->permissions->pluck('code')->contains($permission);
    }
}
