<?php

namespace App\Http\Controllers\Manage;

use App\User;
use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\TicketConversation;
use DB;
use App\TicketNote;
use State;

class TicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    { 
        return view('manage.tickets.index');
    }

    public function list(Request $request)
    {
        $user=auth()->user();

        $filter_state=Input::get('state');
        $filter_find=Input::get('find');
        $filter_catered=Input::get('catered');
        $filter_supports=Input::get('supports');
    
        $tickets=Ticket::on();
        $tickets->select([
            'id',
            'control_number', 
            'sender_name', 
            'sender_email', 
            'sender_carbon_copies', 
            'sender_internet_protocol_address', 
            'sender_factory',
            'sender_phone', 

            'title', 
            'urgency', 
            'content',

            'catered_by', 
            'catered_at', 
            'processing_at', 
            'solution', 
            'solved_at', 
            'created_at',
            'closed_at', 
            'hold_at', 
            'un_hold_at', 
            'user_rating', 
            'user_feedback',
            'state',
        ]);

        if(!empty($filter_state))
        {
            if($filter_state!="-1")
                $tickets->where('state','=',$filter_state);
        }
        else
        {
            $tickets->where('state','<>',State::CANCELLED); # Must not be cancelled
            $tickets->where('state','<>',State::CLOSED); # Must not be closed
        }

        if(!empty($filter_find))
        {
            $tickets->where(function($query)use($filter_find){
                $query->orWhere('control_number','like','%'.$filter_find.'%')
                ->orWhere('title','like','%'.$filter_find.'%')
                ->orWhere('sender_name','like','%'.$filter_find.'%')
                ->orWhere('sender_email','like','%'.$filter_find.'%')
                ->orWhere('sender_internet_protocol_address','like','%'.$filter_find.'%')
                ->orWhere('sender_phone','like','%'.$filter_find.'%');
            });
        }

        # If not administrator then limit the list per account and pending tickets only
        if(!$user->can('manage-ticket') || ($filter_catered==="true" || $filter_catered===true))
            $tickets->where(function($query)use($user){
                $query->orWhere('catered_by','=',$user->id);
                $query->orWhere('state','=',State::PENDING);
            });
        else {
            if(!empty($filter_supports))
            {
                $tickets->where(function($query)use($user,$filter_supports){
                    $query->orWhereIn('catered_by',$filter_supports);
                    $query->orWhere('state','=',State::PENDING);
                }); 
            }
        }
        
   
        $tickets->with('caterer');
        
        $tickets->orderBy('state','asc')
        ->orderBy('updated_at','asc');

        return datatables($tickets)->rawColumns([
            'urgency','state','user_rating',
        ])->toJson();
    }

    
    /**
     * GET THE SAVED NOTES OF THE TICKET
     * @param $id The database id of the ticket
     * @return json
     */
    public function notes($id)
    {
        $notes=TicketNote::where('ticket_id',$id)->orderBy('created_at','desc')->get();
        return \json_encode($notes);
    }

    public function view($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');
        return view('manage.tickets.view',['ticket'=>$ticket]);
    }
}
