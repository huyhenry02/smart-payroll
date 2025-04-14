<?php

namespace App\Http\Controllers;

use App\Models\Allowance;
use App\Models\Bonus;
use App\Models\Deduction;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\User;
use App\Models\WorkingShift;
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

    public function searchEmployee(Request $request): View|Factory|Application
    {
        $keySearch = $request->input('keySearch');
        $status = $request->input('status');
        $role = $request->input('role');
        $department = $request->input('department');
        $position = $request->input('position');

        $users = User::with('employee')
            ->when($keySearch, function ($query) use ($keySearch) {
                $query->where('email', 'like', "%$keySearch%")
                    ->orWhereHas('employee', function ($q) use ($keySearch) {
                        $q->where('full_name', 'like', "%$keySearch%")
                            ->orWhere('phone', 'like', '%' . $keySearch . '%');
                    });
            })
            ->when($status, function ($query, $status) {
                return $query->whereHas('employee', function ($query) use ($status) {
                    $query->where('status', $status);
                });
            })
            ->when($role, function ($query, $role) {
                $query->where('role', $role);
            })
            ->when($department, function ($query, $department) {
                return $query->whereHas('employee', function ($query) use ($department) {
                    $query->where('department_id', $department);
                });
            })
            ->when($position, function ($query, $position) {
                return $query->whereHas('employee', function ($query) use ($position) {
                    $query->where('position_id', $position);
                });
            })->paginate(10);
        return view('page.general_catalog.employee.index-table', compact('users'));
    }

    public function showIndexWorkingShift(): View|Factory|Application
    {
        $workingShifts = WorkingShift::all();
        return view('page.general_catalog.working_shift.index', [
            'workingShifts' => $workingShifts,
        ]);
    }

    public function deleteWorkingShift(WorkingShift $workingShift): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $workingShift->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexWorkingShift')->with('success', 'Xóa ca làm việc thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexWorkingShift')->with('error', 'Xóa ca làm việc thất bại');
        }
    }

    public function putWorkingShift(WorkingShift $workingShift, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $workingShift->fill($input);
            $workingShift->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexWorkingShift')->with('success', 'Cập nhật ca làm việc thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexWorkingShift')->with('error', 'Cập nhật ca làm việc thất bại');
        }
    }

    public function showIndexDeduction(): View|Factory|Application
    {
        $deductions = Deduction::all();
        return view('page.general_catalog.deduction.index', [
            'deductions' => $deductions,
        ]);
    }

    public function deleteDeduction(Deduction $deduction): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $deduction->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDeduction')->with('success', 'Xóa khoản trích nộp theo lương thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDeduction')->with('error', 'Xóa khoản trích nộp theo lương thất bại');
        }
    }

    public function putDeduction(Deduction $deduction, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            if (!empty($input['rate'])) {
                $input['rate'] /= 100;
            }
            $deduction->fill($input);
            $deduction->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDeduction')->with('success', 'Cập nhật trích nộp theo lương thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDeduction')->with('error', 'Cập nhật trích nộp theo lương thất bại');
        }
    }

    public function postDeduction(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $input['rate'] /= 100;
            $deduction = new Deduction();
            $deduction->fill($input);
            $deduction->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexDeduction')->with('success', 'Tạo trích nộp theo lương thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexDeduction')->with('error', 'Tạo trích nộp theo lương thất bại');
        }
    }

    public function showIndexAllowance(): View|Factory|Application
    {
        $allowances = Allowance::all();
        return view('page.general_catalog.allowance.index', [
            'allowances' => $allowances,
        ]);
    }

    public function deleteAllowance(Allowance $allowance): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $allowance->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexAllowance')->with('success', 'Xóa khoản phụ cấp, trợ cấp thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexAllowance')->with('error', 'Xóa khoản phụ cấp, trợ cấp thất bại');
        }
    }

    public function putAllowance(Allowance $allowance, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $allowance->fill($input);
            $allowance->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexAllowance')->with('success', 'Cập nhật phụ cấp, trợ cấp thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexAllowance')->with('error', 'Cập nhật phụ cấp, trợ cấp thất bại');
        }
    }

    public function postAllowance(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $allowance = new Allowance();
            $allowance->fill($input);
            $allowance->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexAllowance')->with('success', 'Tạo phụ cấp, trợ cấp thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexAllowance')->with('error', 'Tạo phụ cấp, trợ cấp thất bại');
        }
    }

    public function showIndexBonus(): View|Factory|Application
    {
        $bonuses = Bonus::all();
        return view('page.general_catalog.bonus.index', [
            'bonuses' => $bonuses,
        ]);
    }

    public function deleteBonus(Bonus $bonus): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $bonus->delete();
            DB::commit();
            return redirect()->route('general_catalog.showIndexBonus')->with('success', 'Xóa khoản thưởng nhân viên thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexBonus')->with('error', 'Xóa khoản thưởng nhân viên thất bại');
        }
    }

    public function putBonus(Bonus $bonus, Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $bonus->fill($input);
            $bonus->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexBonus')->with('success', 'Cập nhật thưởng nhân viên thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexBonus')->with('error', 'Cập nhật thưởng nhân viên thất bại');
        }
    }

    public function postBonus(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();
            $input = $request->input();
            $bonus = new Bonus();
            $bonus->fill($input);
            $bonus->save();
            DB::commit();
            return redirect()->route('general_catalog.showIndexBonus')->with('success', 'Tạo thưởng nhân viên thành công');
        } catch (Exception $e) {
            DB::rollBack();
            return redirect()->route('general_catalog.showIndexBonus')->with('error', 'Tạo thưởng nhân viên thất bại');
        }
    }
}
