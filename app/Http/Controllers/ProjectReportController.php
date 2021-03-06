<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use DB;

class ProjectReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('report.tickets');
    }

    public function list(Request $request)
    {
        $date_from=$request['from'];
        $date_to=$request['to'];

        $tickets=Ticket::on();

        if(!empty($date_from) && !empty($date_to))
            $tickets->whereRaw(DB::raw("created_at between '".$date_from."' and '".$date_to."'"));

        return datatables($tickets)->toJson();
    }
}
