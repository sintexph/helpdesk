<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketConversation;
use App\Ticket;

class InboxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $ticket_conversations=Ticket::whereHas('conversations',function($condition){
            $condition->where('created_by',auth()->user()->id);
        })
        ->select([
            'id',
            'control_number',
            'title',
            'sender_name',
            'created_at'
        ])
        ->paginate(10);
         

        return view('inbox.index',[
            'ticket_conversations'=>$ticket_conversations
        ]);
    }

    public function view($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        return view('inbox.view',['ticket'=>$ticket]);
    }
    
}
