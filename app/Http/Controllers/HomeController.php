<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UserRole;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        # Redirect the user based on the role
        $user=auth()->user();
        switch ($user->role) {
            case UserRole::SENDER:
                return redirect()->route('user');
                break;
            default:
                return redirect()->route('tickets');
                break;
        }
    }
}
