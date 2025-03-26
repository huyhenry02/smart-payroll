<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;

class GeneralCatalogController extends Controller
{
    public function showIndexDepartmentPosition(): View|Factory|Application
    {
        return view('page.general_catalog.department_position.index');
    }
    public function showCreateDepartment(): View|Factory|Application
    {
        return view('page.general_catalog.department_position.department.create');
    }
    public function showUpdateDepartment(): View|Factory|Application
    {
        return view('page.general_catalog.department_position.department.update');
    }
    public function showCreatePosition(): View|Factory|Application
    {
        return view('page.general_catalog.department_position.position.create');
    }
    public function showUpdatePosition(): View|Factory|Application
    {
        return view('page.general_catalog.department_position.position.update');
    }
}
