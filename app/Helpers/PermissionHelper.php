<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermissionHelper
{
    public static function can(string $permissionCode): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }
        if ($user->role === 'director') {
            return true;
        }
        if (!$user->roleInfo || !$user->roleInfo->permissions) {
            return false;
        }
        $userPermissions = $user->roleInfo->permissions->pluck('code')->toArray();
        return in_array($permissionCode, $userPermissions, true);
    }
}
