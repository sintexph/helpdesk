<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Helpers\DashboardHelper;
use App\Ticket;
use App\Helpers\State;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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

    public function top_category_request()
    {
        return json_encode(DashboardHelper::top_category_request());
    }

    public function statistics()
    {
        $users=User::count();
        $tickets=Ticket::where('state','<>',State::CANCELLED)->count();
        $pending_tickets=Ticket::where('state','=',State::PENDING)->count();
        $closed_tickets=Ticket::where('state','=',State::CLOSED)->count();

        return \json_encode([
            'users'=>$users,
            'tickets'=>$tickets,
            'pending_tickets'=>$pending_tickets,
            'closed_tickets'=>$closed_tickets,
        ]);
    }
}
