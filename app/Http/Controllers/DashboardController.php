<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\DashboardHelper;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function staff_performance()
    {
        $users=User::limit(8)->get();
        return json_encode($users);
    }

    public function rating_summary()
    {
        return json_encode(DashboardHelper::rating_summary());
    }
}
