<?php

namespace App\Http\Controllers\Manage;

use App\Ticket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\TicketActionHelper;
use DB;
use App\TicketNote;
use App\User;
use App\TicketReference;
use TicketState;

class TicketActionController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CATER TICKET
     * @param $data Holds the option for catering or cater and process
     * @param $id The database id of the ticket
     * Condition: Can cater only of the status of the ticket is pending
     *            Cannot cater if catered
     *            Cannot process if cancelled- Logic will be on general middleware
     * 
     * @return json response
     */
    public function cater(Request $data,$id)
    {
        $this->validate($data,[
          'processed'=>'required|boolean',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');
        abort_if($ticket->state==TicketState::CATERED,400,'Ticket was already catered!');

        try {

            DB::beginTransaction();

            TicketActionHelper::cater($ticket,auth()->user(),$data['processed']);

            DB::commit();
            return response()->json(['message'=>'Ticket has been successfully catered!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }   
    }
    

    /**
     * PROCESS THE TICKET
     * @param $id The database id of the ticket
     * Condition: Can process if the ticket state is catered
     *            Cannot process if processed
     *            Cannot process if cancelled- Logic will be on general middleware
     * 
     * @return json response
     */
    public function process($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');
        # Check first if the ticket is already processing
        abort_if($ticket->state==TicketState::PROCESSING,400,'Ticket was already processing!');
        abort_if($ticket->state!=TicketState::CATERED,400,'Ticket must be catered first before processing!');
        
        TicketActionHelper::process($ticket,auth()->user());


        return response()->json(['message'=>'Ticket has been successfully updated to processing!']);
    }

    /**
     * SOLVE THE TICKET
     * @param $data 
     * @param $id The database id of the ticket
     * Condition: Can solve if the ticket is processing
     *            Cannot solve if solve already
     *            Cannot solve the ticket if put onhold
     *            Cannot solve if cancelled- Logic will be on general middleware
     * 
     * @return json response
     */
    public function solve(Request $data,$id)
    {
       $this->validate($data,[
          'category'=>'required',
          'solution'=>'required',
       ]);
       
       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');

       abort_if($ticket->state==TicketState::SOLVED,400,'Ticket was already solved!');
       abort_if($ticket->state==TicketState::HOLD,400,'Ticket is still on hold!');
       
       
       try {
           DB::beginTransaction();
           
           TicketActionHelper::solve($ticket,auth()->user(),$data['category'],$data['solution']);

           DB::commit();
           
           return response()->json(['message'=>'Ticket has been successfully solved!']);

        }catch (\Throwable $e) {
            
            DB::rollBack();
            abort(400,$e->getMessage());

        }

       
    }


    /**
     * HOLD THE TICKET
     * @param $id The database id of the ticket
     * Condition: Can hold the ticket if not solved yet
     *            Can hold the ticket if is processing or catered
     *            Cannot solve if cancelled- Logic will be on general middleware      
     */
    public function hold($id)
    {
       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');

       abort_if($ticket->state==TicketState::HOLD,400,'Ticket was already put on hold');
       abort_if($ticket->state!=TicketState::PROCESSING && $ticket->state!=TicketState::CATERED,400,'Cannot hold the ticket if it was not processing or catered');


       try {

           DB::beginTransaction();
           TicketActionHelper::hold($ticket,auth()->user());
           DB::commit();

           return response()->json(['message'=>'Ticket has been successfully put on hold!']);
           
       }catch (\Throwable $th) {
           DB::rollBack();
           abort(400,$th->getMessage());
       }
    }

    /**
     * UN-HOLD THE HOLD TICKET
     * @param $id The database id of the ticket
     * Condition: Can un hold the ticket it was hold
     *            Cannot solve if cancelled- Logic will be on general middleware  
     */
    public function un_hold($id)
    {
       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');
       # Cannot un hold if not hold
       abort_if($ticket->state!=TicketState::HOLD,400,'Hold the ticket first before un-holding!');
       

       TicketActionHelper::un_hold($ticket,auth()->user());


       return response()->json(['message'=>'Ticket has been successfully un-hold!']);
    }

    /**
     * CANCEL THE TICKET
     * @param $request Holds cancellation reason
     * @param $id The database id of the ticket
     * Condition: Can cancel if the ticket is not yet cancelled
     *            Can cancel the ticket if not solved yet
     *            Cannot solve if cancelled- Logic will be on general middleware    
     * 
     * @return json response
     */
    public function cancel(Request $request,$id)
    {
        $this->validate($request,[
            'cancellation_reason'=>'required',
        ]);

       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');
       abort_if($ticket->state==TicketState::CANCELLED,400,'Ticket was already cancelled!');
       abort_if($ticket->state==TicketState::SOLVED,400,'Ticket was already solved and could not be cancelled anymore!');


       TicketActionHelper::cancel($ticket,auth()->user(),$request['cancellation_reason']);



       return response()->json(['message'=>'Ticket has been successfully cancelled!']);
    }


    /**
     * OPEN THE TICKET AFTER UPDATED AS SOLVED BY THE SUPPORT STAFF
     * @param $request Holds the reason for opening
     * @param $id The database id of the ticket
     * Condition: Cannot open if the ticket is cancelled
     *            Cannot open if the ticket is closed
     *            Can open only if the ticket if it is solved
     * @return json response
     */
    public function open(Request $request,$id)
    {
        $this->validate($request,[
            'reason'=>'required',
        ]);
    
        $ticket=Ticket::find($id);

        abort_if($ticket==null,404,'Ticket could not be found!');
        abort_if($ticket->state==TicketState::CLOSED,400,'The ticket was already closed and could not be cancelled anymore!');
        abort_if($ticket->state==TicketState::CANCELLED,400,'Ticket was already cancelled!');

        

        # Ticket must be solved first before it can be opened
        if($ticket->state==TicketState::SOLVED)
        {
            TicketActionHelper::open($ticket,$request['reason']);
        }
        else
            abort(400,'Ticket must be solved first before it can be opened again!');

        return response()->json(['message'=>'Ticket has been successfully re opened!']);
    }


    /**
     * ADD NOTE TO THE TICKET
     * @param $request holds the notes to be saved
     * @param $id The database id of the ticket
     */
    public function add_note(Request $request,$id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        $this->validate($request,[
            'note'=>'required',
        ]);


       try {
           
            DB::beginTransaction();

            # Anyone can escalate the ticket not limited to the caterer
            TicketActionHelper::add_note($request['note'],$ticket,auth()->user());

            DB::commit();

           return response()->json(['message'=>'Note has been successfully added to the ticket!']);
           
       }catch (\Throwable $th) {
           DB::rollBack();
           abort(400,$th->getMessage());
       }


        
    }

    public function remove_note($id)
    {
        $note=TicketNote::find($id);
        abort_if($note==null,404,'Note could not be found!');
        $note->delete();

        return response()->json(['message'=>'Note has been successfully removed!']);
    }

    public function update_note(Request $request,$id)
    {
        $note=TicketNote::find($id);
        abort_if($note==null,404,'Note could not be found!');

        $this->validate($request,[
            'note'=>'required',
        ]);

        $note->content=$request['note'];
        $note->save();

        return response()->json(['message'=>'Note has been successfully updated!']);
    }

    
    /**
     * ESCALATE TO OTHER SUPPORT STAFF
     * @param $request Holds the user id of the staff to be escalated
     * @param $id The database id of the ticket
     * 
     * Condition: Could not be escalated if ticket is cancelled, solved, or closed
     *            Could escalate if the ticket is catered
     *            Could not escalate if the authenticated user is the caterer
     */
    public function escalate(Request $request,$id)
    {
        $this->validate($request,[
            'user_id'=>'required',
        ]);
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');


        if($ticket->state==TicketState::PENDING)
            abort(400,'Ticket must be catered first before escalating!');
        if($ticket->state==TicketState::SOLVED)
            abort(400,'The ticket was already solved and could not be escalated anymore!');
        elseif($ticket->state==TicketState::CLOSED)
            abort(400,'The ticket was already closed and could not be escalated anymore!');
        elseif($ticket->state==TicketState::CANCELLED)
            abort(400,'The ticket was already cancelled and could not be escalated anymore!');

            
        $escalate_to=User::find($request['user_id']);
        abort_if($escalate_to==null,404,'Support staff selected could not be found on the system!');
        abort_if($escalate_to->id==$ticket->catered_by,400,'You mus select different support staff!');

       try {
           
            DB::beginTransaction();

            # Anyone can escalate the ticket not limited to the caterer
            TicketActionHelper::escalate($ticket,auth()->user(),$escalate_to);

            DB::commit();

           return response()->json(['message'=>'Ticket has been successfully escalated!']);
           
       }catch (\Throwable $th) {
           DB::rollBack();
           abort(400,$th->getMessage());
       }
    }


    /**
     * ADD REFERENCE TICKET TO THE TICKET
     * @param $request Holds the ticket control number to be added as reference
     * @param $id The database id of the ticket
     * 
     * Condition: Could not add reference if ticket is cancelled, solved, or closed and pending
     * 
     */
    public function add_reference(Request $request,$id)
    {
        $this->validate($request,[
            'control_number'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found');
        $reference_ticket=Ticket::where('control_number',$request['control_number'])->first();
        abort_if($reference_ticket==null,404,'Reference ticket could not be found!');

        
        if($ticket->state==TicketState::PENDING)
            abort(400,'Ticket must be catered first before adding reference!');
        elseif($ticket->state==TicketState::SOLVED)
            abort(400,'The ticket was already solved and could not add reference anymore!');
        elseif($ticket->state==TicketState::CLOSED)
            abort(400,'The ticket was already closed and could not add reference anymore!');
        elseif($ticket->state==TicketState::CANCELLED)
            abort(400,'The ticket was already cancelled and could not add reference anymore!');

        $exists=TicketReference::where('ticket_reference_id',$reference_ticket->id)->exists();
        abort_if($exists==true,400,'The ticket is already added as reference!');

        TicketReference::create([
            'ticket_id'=>$ticket->id,
            'ticket_reference_id'=>$reference_ticket->id,
            'created_by'=>auth()->user()->name,
        ]);

        return response()->json(['message'=>'Ticket reference has been successfully added!']);
    }


    /**
     * APPLY APPROVAL FOR THE TICKET
     * @param $request Holds the ticket control number to be added as reference
     * @param $id The database id of the ticket
     * 
     * Condition: Could not apply if already applied and ticket is cancelled, solved, or closed and pending
     */
    public function apply_approval(Request $request,$id)
    {
        $this->validate($request,[
            'approver_email'=>'required',
            'approver_name'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        if($ticket->state==TicketState::PENDING)
            abort(400,'Ticket must be catered first before it can be applied for approval!');
        elseif($ticket->state==TicketState::SOLVED)
            abort(400,'The ticket was already solved and could not apply approval anymore!');
        elseif($ticket->state==TicketState::CLOSED)
            abort(400,'The ticket was already closed and could not apply approval anymore!');
        elseif($ticket->state==TicketState::CANCELLED)
            abort(400,'The ticket was already cancelled and could not apply approval anymore!');
        
        if($ticket->approver_name!==null)
            abort(400,'The ticket was already applied for approval!');
        elseif($ticket->approved_at!==null)
            abort(400,'The ticket was already approved!');

        try {

            DB::beginTransaction();

            TicketActionHelper::apply_approval($ticket,auth()->user(),$request['approver_name'],$request['approver_email']);

            DB::commit();
            
            return response()->json(['message'=>'Ticket has been successfully applied for approval!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
    

    /**
     * CANCEL APPROVAL FOR THE TICKET
     * @param $request Holds the ticket control number to be added as reference
     * @param $id The database id of the ticket
     * 
     * Condition: Could not apply if already applied and ticket is cancelled, solved, or closed and pending
     */
    public function cancel_approval(Request $request,$id)
    { 
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        if($ticket->state==TicketState::PENDING)
            abort(400,'Ticket must be catered first!');
        elseif($ticket->state==TicketState::SOLVED)
            abort(400,'The ticket was already solved!');
        elseif($ticket->state==TicketState::CLOSED)
            abort(400,'The ticket was already closed!');
        elseif($ticket->state==TicketState::CANCELLED)
            abort(400,'The ticket was already cancelled!');
        
        if($ticket->approver_name===null)
            abort(400,'Cancellation could not be executed since there has no approval applied!');
        elseif($ticket->approved_at!==null)
            abort(400,'The ticket was already approved and could not be cancelled anymore!');

        try {

            DB::beginTransaction();

            TicketActionHelper::cancel_approval($ticket,auth()->user());

            DB::commit();
            
            return response()->json(['message'=>'Ticket approval has been successfully cancelled!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
    

    /**
     * SAVE THE TICKET CREATED BY SUPPORT STAFF
     */
    public function save(Request $request)
    {
        $this->validate($request,[
            'sender_id_number'=>'required',
            'sender_internet_protocol_address'=>'required',
            'sender_phone'=>'required',
            'title'=>'required|max:50',
            'content'=>'required',
            'urgency'=>'required',
        ]);
        
        try {
            DB::beginTransaction(); 

            $sender=User::where('id_number',$request['sender_id_number'])->first();
            abort_if($sender==null,400,'Could not find the sender on the system '.$request['sender_id_number']);

            $attachments=$request['attachments'];
            $carbon_copies=explode(',',$request['sender_carbon_copies']); 

            $ticket=TicketActionHelper::create(
                $sender->name,
                $sender->email,
                $carbon_copies,
                $request['sender_internet_protocol_address'],
                $request['sender_phone'],
                $sender->factory,
                $request['title'],
                $request['content'],
                $request['urgency'],
                $attachments 
            );

            DB::commit();     
                   
            return response()->json([
                'message'=>'Ticket has been successfully submitted<br>You will be redirected to ticket page in a few moment....',
                'control_number'=>$ticket->control_number,
                'token'=>$ticket->token,
            ]);

        }
        catch (\Exception $e) {

            DB::rollBack();
            abort(400,$e->getMessage());
        }        
    }

    public function custom_progress(Request $request,$id)
    {
        $this->validate($request,['state'=>'required']);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        if($ticket->state==TicketState::PENDING)
            abort(400,'Ticket must be catered first!');
        elseif($ticket->state==TicketState::SOLVED)
            abort(400,'The ticket was already solved!');
        elseif($ticket->state==TicketState::CLOSED)
            abort(400,'The ticket was already closed!');
        elseif($ticket->state==TicketState::CANCELLED)
            abort(400,'The ticket was already cancelled!');
         elseif($ticket->state!=TicketState::PROCESSING)
            abort(400,'The ticket must be processing first before can set custom progress!');

        $state='TicketState::'.strtoupper($request['state']);
        if(!defined($state))
            abort(400,'Progress is invalid!');

        # Get the state constant dynamically
        $state=constant($state);
        
        try {

            DB::beginTransaction();
            
            TicketActionHelper::custom_progress($ticket,auth()->user(),$state);

            DB::commit();
            
            return response()->json(['message'=>'Ticket progress has been successfully saved!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
}
