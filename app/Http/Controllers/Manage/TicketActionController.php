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
use State;
use App\Helpers\TicketActionCondition;

class TicketActionController extends Controller
{
    use TicketActionCondition;
    
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * CATER TICKET
     * @param $data Holds the option for catering or cater and process
     * @param $idThe database id of the ticket
     * @return json response
     */
    public function cater(Request $data,$id)
    {
        $this->validate($data,[
          'processed'=>'required|boolean',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        # Apply condition
        $this->condition_cater($ticket);

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
     * @param $idThe database id of the ticket
     * @return json response
     */
    public function processing($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        # Apply condition
        $this->condition_process($ticket);
        
        TicketActionHelper::processing($ticket,auth()->user());


        return response()->json(['message'=>'Ticket has been successfully updated to processing!']);
    }

    /**
     * SOLVE THE TICKET
     * @param $data 
     * @param $idThe database id of the ticket
     * @return json response
     */
    public function solve(Request $data,$id)
    {
        $validation=[
          'category'=>'required',
          'solution'=>'required',
        ];

        if($data->message_to_sender==="true" || $data->message_to_sender===true)
        {
            $data->message_to_sender=true;
            $validation['message']=[
                'required',
                function($attribute,$value,$fail)
                {
                    if(empty(str_replace(" ","",preg_replace('/[^A-Za-z0-9\-]/', '', strip_tags($value)))))
                        $fail('The '.$attribute.' field is required.');
                }
            ];
        }

        $this->validate($data,$validation);
        
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        # Apply condition
        $this->condition_solve($ticket);

        try {
            DB::beginTransaction();
            
            TicketActionHelper::solve($ticket,auth()->user(),$data->category,$data->solution,$data->message_to_sender,$data->message);

            DB::commit();
            
            return response()->json(['message'=>'Ticket has been successfully solved!']);

            }catch (\Throwable $e) {
                
                DB::rollBack();
                abort(400,$e->getMessage());

            }

       
    }


    /**
     * HOLD THE TICKET
     * @param $idThe database id of the ticket  
     */
    public function hold($id)
    {
       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');

       # Apply condition
       $this->condition_hold($ticket);

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
     * @param $idThe database id of the ticket
     */
    public function un_hold($id)
    {
       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');

       # Apply condition
       $this->condition_un_hold($ticket);
       
       TicketActionHelper::un_hold($ticket,auth()->user());

       return response()->json(['message'=>'Ticket has been successfully un-hold!']);
    }

    /**
     * CANCEL THE TICKET
     * @param $request Holds cancellation reason
     * @param $idThe database id of the ticket
     * @return json response
     */
    public function cancel(Request $request,$id)
    {
        $this->validate($request,[
            'cancellation_reason'=>'required',
        ]);

       $ticket=Ticket::find($id);
       abort_if($ticket==null,404,'Ticket could not be found!');

       # Apply condition
       $this->condition_cancel($ticket);

       TicketActionHelper::cancel($ticket,auth()->user(),$request['cancellation_reason']);

       return response()->json(['message'=>'Ticket has been successfully cancelled!']);
    }


    /**
     * OPEN THE TICKET AFTER UPDATED AS SOLVED BY THE SUPPORT STAFF
     * @param $request Holds the reason for opening
     * @param $idThe database id of the ticket
     * @return json response
     */
    public function open(Request $request,$id)
    {
        $this->validate($request,[
            'reason'=>'required',
        ]);
    
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        # Apply condition
        $this->condition_open($ticket);

        TicketActionHelper::open($ticket,auth()->user(),$request['reason']);
            
            

        return response()->json(['message'=>'Ticket has been successfully re opened!']);
    }

    /**
     * ESCALATE TO OTHER SUPPORT STAFF
     * @param $request HOLDS THE USER ID OF THE STAFF TO BE ESCALATED
     * @param $idTHE DATABASE ID OF THE TICKET
     * 
     * ANYONE CAN ESCALATE THE TICKET NOT LIMITED TO THE CATERER
     */
    public function escalate(Request $request,$id)
    {
        $this->validate($request,[
            'user_id'=>'required',
        ]);
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        # Get the user to be escalated to
        $escalate_to=User::find($request['user_id']);

        # Apply condition
        $this->condition_escalate($ticket,$escalate_to);

        try {
            
                DB::beginTransaction();

                TicketActionHelper::escalate($ticket,auth()->user(),$escalate_to);

                DB::commit();

            return response()->json(['message'=>'Ticket has been successfully escalated!']);
            
        }catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    /**
     * MODIFY CARBON COPIES
     */
    public function modify_carbon_copies(Request $request,$id)
    {
        $this->validate($request,[
            'sender_carbon_copies'=>'nullable',
        ]);
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

     
        # Apply condition
        $this->condition_modify_carbon_copies($ticket);

        try {
            
                DB::beginTransaction();

                $carbon_copies=explode(',',$request['sender_carbon_copies']); 
                TicketActionHelper::modify_carbon_copies($ticket,auth()->user(),$carbon_copies);

                DB::commit();

            return response()->json(['message'=>'Carbon copies has been successfully updated!']);
            
        }catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }

    /**
     * CHANGE SENDER
     */
    public function change_sender(Request $request,$id)
    {
        $this->validate($request,[
            'sender'=>'required',
        ]);

        # Cannot change the sender if not admin
        abort_if(auth()->user()->can('admin')==false,401,'You are unauthorized to process the action');

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');

        $sender=User::find($request->sender);
        abort_if($sender==null,404,'Sender could not be found!');
     
        # Apply condition
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and sender could not be changed anymore!');

        try {
            
                DB::beginTransaction();
 
                TicketActionHelper::change_sender($ticket,auth()->user(),$sender);

                DB::commit();

            return response()->json(['message'=>'Carbon copies has been successfully updated!']);
            
        }catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }


    /**
     * ADD REFERENCE TICKET TO THE TICKET
     * @param $request HOLDS THE TICKET CONTROL NUMBER TO BE ADDED AS REFERENCE
     * @param $id THE DATABASE ID OF THE TICKET
     */
    public function add_reference(Request $request,$id)
    {
        
        $this->validate($request,[
            'control_number'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found');

        # Get the reference ticket based on the control number
        $reference=Ticket::where('control_number',$request['control_number'])->first();
        abort_if($reference==null,404,'Reference ticket could not be found!');

        # Apply condition
        $this->condition_add_reference($ticket,$reference);

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
     * @param $idThe database id of the ticket
     */
    public function apply_approval(Request $request,$id)
    {
        $this->validate($request,[
            'approver_email'=>'required',
            'approver_name'=>'required',
        ]);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        # Apply condition
        $this->condition_apply_approval($ticket);

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
     * APPLY APPROVAL FOR THE TICKET
     * @param $request Holds the ticket control number to be added as reference
     * @param $idThe database id of the ticket
     */
    public function resend_approval(Request $request,$id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        # Apply condition
        $this->condition_resend_approval($ticket);

        try {

            DB::beginTransaction();

            TicketActionHelper::resend_approval($ticket,auth()->user());

            DB::commit();
            
            return response()->json(['message'=>'Ticket approval has been successfully resend!']);
        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }
    }
    
    /**
     * CANCEL APPROVAL FOR THE TICKET
     * @param $request Holds the ticket control number to be added as reference
     * @param $idThe database id of the ticket
     */
    public function cancel_approval(Request $request,$id)
    { 
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        # Apply condition
        $this->condition_cancel_approval($ticket);

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
     * SET A CUSTOM PROGRESS OF THE TICKET
     * @param $request Holds the state
     * @param $idThe database id of the ticket
     */
    public function custom_progress(Request $request,$id)
    {
        $this->validate($request,['state'=>'required']);

        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found'); 

        # Apply condition
        $this->condition_custom_progress($ticket);

        $state='State::'.strtoupper($request['state']);
        if(!defined($state))
            abort(400,'Selected progress is invalid!');

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

    /**
     * SAVE THE TICKET CREATED BY SUPPORT STAFF
     */
    public function save(Request $request)
    {
        $this->validate($request,[
            'sender'=>'required',
            'sender_internet_protocol_address'=>'required',
            'sender_phone'=>'required',
            'title'=>'required|max:50',
            'content'=>'required',
            'urgency'=>'required',
        ]);
        
        try {
            DB::beginTransaction(); 

            $sender=User::find($request['sender']);
            abort_if($sender==null,400,'Could not find the sender on the system '.$request['sender']);

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
                $attachments,
                auth()->user()
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

    /**
     * ADD NOTE TO THE TICKET
     * @param $request holds the notes to be saved
     * @param $idThe database id of the ticket
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
}
