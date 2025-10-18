<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SystemController extends Controller
{
    public function showIndexUser(): View|Factory|Application
    {
        $users = User::paginate(10);
        return view('page.system.user.index',
            [
                'users' => $users,
            ]);
    }

    public function getUser($id): JsonResponse
    {
        $user = User::with(['employee', 'roleInfo'])->findOrFail($id);

        return response()->json([
            'id' => $user->id ?? '',
            'email' => $user->email ?? '',
            'role' => $user->role ?? '',
            'role_id' => $user->role_id ?? '',
            'is_active' => $user->is_active ?? '',
            'full_name' => $user->employee->full_name ?? '',
        ], 200, [], JSON_UNESCAPED_UNICODE);
    }


    public function postUser(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->input();
            $user = new User();
            $user->fill($input);
            $userName = explode('@', $input['email'])[0];
            $user->password = bcrypt($userName . '@123');
            $user->save();

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->full_name = $input['full_name'];
            $employee->employee_code = 'NV-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $employee->save();
            DB::commit();
            return redirect()->route('system.showIndexUser')->with('success', 'Tạo người dùng thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('system.showIndexUser')->with('error', 'Tạo người dùng thất bại');
        }
    }

    public function putUser(Request $request, User $user): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->input();
            $user->fill($input);
            if (!empty($input['password'])) {
                $user->password = bcrypt($input['password']);
                unset($input['confirm_password']);
            }
            $user->save();

            $employee = $user->employee;
            $employee->full_name = $input['full_name'];
            $employee->save();
            DB::commit();
            return redirect()->route('system.showIndexUser')->with('success', 'Cập nhật người dùng thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('system.showIndexUser')->with('error', 'Cập nhật người dùng thất bại');
        }
    }

    public function showIndexRole(): View|Factory|Application
    {
        $roles = Role::paginate(10);
        return view('page.system.role.index', ['roles' => $roles]);
    }

    public function showCreateRole(): View|Factory|Application
    {
        $permissions = Permission::all();
        $permissionsByModule = $permissions->groupBy('module_code')->toArray();
        return view('page.system.role.create', [
            'permissionsByModule' => $permissionsByModule,
        ]);
    }

    public function showUpdateRole(Role $role): View|Factory|Application
    {
        $permissions = Permission::all();
        $permissionsByModule = $permissions->groupBy('module_code')->toArray();
        $assignedPermissions = $role->permissions->pluck('id')->toArray();
        return view('page.system.role.update', [
            'role' => $role,
            'permissionsByModule' => $permissionsByModule,
            'assignedPermissions' => $assignedPermissions,
        ]);
    }

    public function postCreateRole(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $input['is_active'] = 1;
            $input['code'] = 'ROLE-' . str_pad(Role::max('id') + 1, 4, '0', STR_PAD_LEFT);
            $role = new Role();
            $input['created_by'] = auth()->user()->id ?? 0;
            $role->fill($input);
            $role->save();
            if (!empty($input['role_permissions']) && is_array($input['role_permissions'])) {
                $role->permissions()->attach($input['role_permissions']);
            }
            DB::commit();
            return redirect()->route('system.showIndexRole')->with('success', 'Tạo nhóm quyền thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('system.showCreateRole')->with('error', 'Tạo nhóm quyền thất bại');
        }
    }

    public function putCreateRole(Request $request, Role $role): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $role->fill($input);
            $role->save();
            if (!empty($input['role_permissions']) && is_array($input['role_permissions'])) {
                $role->permissions()->attach($input['role_permissions']);
            }
            DB::commit();
            return redirect()->route('system.showIndexRole')->with('success', 'Tạo nhóm quyền thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('system.showCreateRole')->with('error', 'Tạo nhóm quyền thất bại');
        }
    }

    public function deleteRole(Role $role): RedirectResponse
    {
        try {
            DB::beginTransaction();
            if ($role->users()->count() > 0) {
                return redirect()->route('system.showIndexRole')->with('error', 'Không thể xóa nhóm quyền đang có người dùng sử dụng');
            }
            $role->permissions()->detach();
            $role->delete();
            DB::commit();
            return redirect()->route('system.showIndexRole')->with('success', 'Xóa nhóm quyền thành công');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()->route('system.showIndexRole')->with('error', 'Xóa nhóm quyền thất bại');
        }
    }
}
