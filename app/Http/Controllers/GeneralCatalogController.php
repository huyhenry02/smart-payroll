<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralCatalogController extends Controller
{
    public function showIndexDepartment(): View|Factory|Application
    {
        $departments = Department::all();
        return view('page.general_catalog.department_position.department.index',
            ['departments' => $departments]);
    }

    public function showIndexPosition(): View|Factory|Application
    {
        $positions = Position::all();
        return view('page.general_catalog.department_position.position.index',
            ['positions' => $positions]);
    }

    public function postDepartment(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $department = new Department();
            $department->fill($input);
            $department->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDepartment')->with('success', 'Tạo phòng ban thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDepartment')->with('error', 'Tạo phòng ban thất bại');
        }
    }

    public function postPosition(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $position = new Position();
            $position->fill($input);
            $position->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexPosition')->with('success', 'Tạo chức vụ thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexPosition')->with('error', 'Tạo chức vụ thất bại');
        }
    }

    public function deleteDepartment(Department $department): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $department->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDepartment')->with('success', 'Xóa phòng ban thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDepartment')->with('error', 'Xóa phòng ban thất bại');
        }
    }

    public function deletePosition(Position $position): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $position->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexPosition')->with('success', 'Xóa chức vụ thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexPosition')->with('error', 'Xóa chức vụ thất bại');
        }
    }

    public function putDepartment(Department $department, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $department->fill($input);
            $department->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDepartment')->with('success', 'Cập nhật phòng ban thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDepartment')->with('error', 'Cập nhật phòng ban thất bại');
        }
    }

    public function putPosition(Position $position, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $position->fill($input);
            $position->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexPosition')->with('success', 'Cập nhật chức vụ thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexPosition')->with('error', 'Cập nhật chức vụ thất bại');
        }
    }

    public function showIndexEmployee(): View|Factory|Application
    {
        $users = User::paginate(10);
        $departments = Department::all();
        $positions = Position::all();
        return view('page.general_catalog.employee.index',
            [
                'users' => $users,
                'departments' => $departments,
                'positions' => $positions,
            ]);
    }

    public function showUpdateEmployee(User $user): View|Factory|Application
    {
        return view('page.general_catalog.employee.update',
            [
                'user' => $user,
                'departments' => Department::all(),
                'positions' => Position::all(),
            ]);
    }

    public function putEmployee(Employee $employee, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $employee->fill($input);
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $fileName = 'avatar_' . $employee->id . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storePubliclyAs('avatar/', $fileName);
                $avatar = asset('storage/' . $filePath);
            }
            $employee->avatar = $avatar ?? $employee->avatar;
            $employee->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexEmployee')->with('success', 'Cập nhật nhân viên thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Cập nhật nhân viên thất bại');
        }
    }
}
