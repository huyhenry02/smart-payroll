<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

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
}
