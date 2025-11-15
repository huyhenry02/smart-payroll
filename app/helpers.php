<?php

use App\Helpers\PermissionHelper;

if (!function_exists('can')) {
    function can(string $permissionCode): bool
    {
        return PermissionHelper::can($permissionCode);
    }
}
