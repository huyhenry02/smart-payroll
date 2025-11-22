<?php

namespace App\Http\Middleware;

use App\Helpers\PermissionHelper;
use Closure;
use Illuminate\Support\Facades\Request;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissionCode)
    {
        if (!PermissionHelper::can($permissionCode)) {
            abort(403, 'Bạn không có quyền truy cập hành động này');
        }

        return $next($request);
    }
}
