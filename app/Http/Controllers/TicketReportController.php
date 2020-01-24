<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use DB;
use App\UserEfficiency;
use App\Helpers\UserRole;
use App\Helpers\StatisticsHelper;
use State;

class TicketReportController extends Controller
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
        $from=$request->from;
        $to=$request->to;
        $support=$request->support;

        $tickets=Ticket::on();
        $tickets->whereRaw(DB::raw("date(created_at) between '".$from."' and '".$to."'"));
        $tickets->with('caterer');

        if(!empty($support))
        {
            $tickets->where('catered_by',$support);
        }


        return datatables($tickets)->toJson();
    }

    public function users_efficiency(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        if(empty($from) || empty($to))
            return \json_encode([]);;
            
        $users=UserEfficiency::join("tickets",'tickets.catered_by','=','user_efficiencies.id');
        $users->where('tickets.state','<>',State::CANCELLED);
        $users->whereRaw(DB::raw("date(tickets.created_at) between '".$from."' and '".$to."'"));
        $users=$users->get()->makeHidden(['tickets']);
        
        return json_encode($users);
    }

   

    public function efficiency_breakdown(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        $support=$request->support;

        if(empty($from) || empty($to))
            return \json_encode([]);;
            
        $tickets=Ticket::select([
            DB::raw("SUM(CASE WHEN tickets.ht_catered = 1 THEN 1 ELSE 0 END) AS hit_catered"),
            DB::raw("SUM(CASE WHEN tickets.ht_catered = 0 THEN 1 ELSE 0 END) AS fail_catered"),
            DB::raw("SUM(CASE WHEN tickets.ht_processing = 1 THEN 1 ELSE 0 END) AS hit_processing"),
            DB::raw("SUM(CASE WHEN tickets.ht_processing = 0 THEN 1 ELSE 0 END) AS fail_processing"),
            DB::raw("SUM(CASE WHEN tickets.ht_solved = 1 THEN 1 ELSE 0 END) AS hit_solved"),
            DB::raw("SUM(CASE WHEN tickets.ht_solved = 0 THEN 1 ELSE 0 END) AS fail_solved"),
            DB::raw("SUM(CASE WHEN tickets.ht_closed = 1 THEN 1 ELSE 0 END) AS hit_closed"),
            DB::raw("SUM(CASE WHEN tickets.ht_closed = 0 THEN 1 ELSE 0 END) AS fail_closed"),
            DB::raw("count(tickets.id) as total_tickets"),
        ]);
        $tickets->where('tickets.state','<>',State::CANCELLED);
        $tickets->whereRaw(DB::raw("date(tickets.created_at) between '".$from."' and '".$to."'"));

        if(!empty($support))
        {
            $tickets->where('tickets.catered_by',$support);
            $tickets->groupBy("tickets.catered_by");
        }
        

        $tickets=$tickets->first();
        

        if($tickets==null)
            return null;
        $tickets->setHidden(['last_state']);

        return json_encode($tickets);
    }
    /**
     * Get the summarize of ratings in a week
     */
    public static function rating_summary(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        $support=$request->support;
        if(empty($from) || empty($to))
            return \json_encode([]);;
        
        if(empty($support))
            $support=null;

        return json_encode(StatisticsHelper::rating_summary(null,$support,$from,$to));
    }
    /**
     * Top 5 category request in a week
     */
    public static function category_request(Request $request)
    {
        $from=$request->from;
        $to=$request->to;
        $support=$request->support;
        if(empty($from) || empty($to))
            return \json_encode([]);;
        if(empty($support))
            $support=null;

        return json_encode(StatisticsHelper::top_category_request(5,null,$support,$from,$to));
    }
}
