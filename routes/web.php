<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('page.system..user.index');
});
