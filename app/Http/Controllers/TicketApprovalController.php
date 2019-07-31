<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\Helpers\TicketActionHelper;
use DB;

class TicketApprovalController extends Controller
{
    public function __construct()
    {
        
    }

    public function index($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found');
        abort_if($ticket->approved_at!=null && session('success')==null && session('error') ==null,400,'Url was already expired maybe the ticket was already approved, rejected or cancelled!');

        return view('approval.index',['ticket'=>$ticket]);
    }

    /**
     * APPROVE TICKET
     * @param $request Holds the token
     * @param $id Holds the database id of the ticket
     * @param JSON
     */
    public function approve(Request $request,$id)
    {
        $this->validate($request,[
            'token'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found');

        if($ticket->approved_at!=null)
            return redirect()->back()->with('error','The ticket was been approved already!');

        if($ticket->token!=trim($request['token']))
            return redirect()->back()->withErrors(['token'=>'Token could not be authenticated!']);
        
        try {

            DB::beginTransaction();

            TicketActionHelper::approved($ticket); 

            DB::commit();

            return redirect()->back()->with('success','Ticket has been successfully approved!');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    /**
     * REJECT TICKET
     * @param $request Holds the token
     * @param $id Holds the database id of the ticket
     * @param JSON
     */
    public function reject(Request $request,$id)
    {
        $this->validate($request,[
            'token'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found');

        if($ticket->approver_email==null || $ticket->approver_name)
            return redirect()->back()->with('error','The ticket was not applied for approval!');

        if($ticket->approved_at!=null)
            return redirect()->back()->with('error','The ticket was been approved already!');

        if($ticket->token!=trim($request['token']))
            return redirect()->back()->withErrors(['token'=>'Token could not be authenticated!']);
        
        try {

            DB::beginTransaction();

            TicketActionHelper::approved($ticket); 

            DB::commit();

            return redirect()->back()->with('success','Ticket has been successfully approved!');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
}
