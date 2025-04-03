<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
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

    public function postUser(Request $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $input = $request->input();
            $user = new User();
            $user->fill($input);
            $user->password = bcrypt($input['password']);
            $user->save();

            $employee = new Employee();
            $employee->user_id = $user->id;
            $employee->full_name = $input['full_name'];
            $employee->employee_code = 'NV-' . str_pad($user->id, 4, '0', STR_PAD_LEFT);
            $employee->save();
            DB::commit();
            return redirect()->route('system.showIndexUser')->with('success', 'Tạo người dùng thành công');
        }catch (\Exception $exception){
            DB::rollBack();
            return redirect()->route('system.showIndexUser')->with('error', 'Tạo người dùng thất bại');
        }
    }
}
