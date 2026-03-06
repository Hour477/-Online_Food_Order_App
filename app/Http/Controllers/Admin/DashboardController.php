<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the dashboard index.
     */
    public function index()
    {
        return view('dashboard.index');
    }
    
}


