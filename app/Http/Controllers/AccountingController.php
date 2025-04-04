<?php

namespace App\Http\Controllers;

use App\Models\Bonus;
use App\Models\Deduction;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class AccountingController extends Controller
{
    public function showIndex(): View|Factory|Application
    {
        $deductions = Deduction::all()->keyBy('id');
        return view('page.accounting.index',
            [
                'deductions' => $deductions,
            ]);
    }

    public function showEmployeeBonus(): View|Factory|Application
    {
        $bonuses = Bonus::all()->keyBy('id');
        return view('page.accounting.employee_bonus',
            [
                'bonuses' => $bonuses,
            ]);
    }
}
