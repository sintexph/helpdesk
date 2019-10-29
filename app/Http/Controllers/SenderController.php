<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Ticket;
use DB;
use App\Helpers\TicketActionHelper;
use App\Helpers\ApplicationHelper;
use App\CustomRequest;
use App\Helpers\Urgency;
use State;


class SenderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sender.index');
    }

    public function list()
    {
        
        $filter_urgency=Input::get('urgency');
        $filter_state=Input::get('state');
        $filter_find=Input::get('find');

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
            'token',
            'catered_by', 
            'catered_at', 
            'processing_at', 
            'solution', 
            'solved_at', 
            'closed_at', 
            'hold_at', 
            'un_hold_at', 
            'user_rating', 
            'user_feedback',
            'state',
            'created_at',
        ]);
        
        $tickets->where('sender_email','=',auth()->user()->email);

        if(!empty($filter_find))
        {
            $tickets->where(function($find_condition)use($filter_find){
                $find_condition->orWhere('title','like','%'.$filter_find.'%')
                ->orWhere('control_number','like','%'.$filter_find.'%')
                ->orWhere('sender_name','like','%'.$filter_find.'%')
                ->orWhere('sender_email','like','%'.$filter_find.'%')
                ->orWhere('sender_phone','like','%'.$filter_find.'%')
                ->orWhere('sender_carbon_copies','like','%'.$filter_find.'%');
            });
        }

        if(!empty($filter_urgency))
        {
            $tickets->where(function($ticket_q)use($filter_urgency){
                
                if($filter_urgency['LOW']==="true" || $filter_urgency['LOW']===true)
                    $ticket_q->orWhere('urgency',Urgency::LOW);
                if($filter_urgency['HIGH']==="true" || $filter_urgency['HIGH']===true)
                    $ticket_q->orWhere('urgency',Urgency::HIGH);
                if($filter_urgency['NORMAL']==="true" || $filter_urgency['NORMAL']===true)
                    $ticket_q->orWhere('urgency',Urgency::NORMAL);

            });
        }

        if(!empty($filter_state))
        {
            $tickets->where(function($ticket_q)use($filter_state){

                if($filter_state['PENDING']==="true" || $filter_state['PENDING']===true)
                    $ticket_q->orWhere('state',State::PENDING);

                if($filter_state['CATERED']==="true" || $filter_state['CATERED']===true)
                    $ticket_q->orWhere('state',State::CATERED);

                if($filter_state['PROCESSING']==="true" || $filter_state['PROCESSING']===true)
                    $ticket_q->orWhere('state',State::PROCESSING);

                if($filter_state['SOLVED']==="true" || $filter_state['SOLVED']===true)
                    $ticket_q->orWhere('state',State::SOLVED);

                if($filter_state['HOLD']==="true" || $filter_state['HOLD']===true)
                    $ticket_q->orWhere('state',State::HOLD);

                if($filter_state['CLOSED']==="true" || $filter_state['CLOSED']===true)
                    $ticket_q->orWhere('state',State::CLOSED);

                if($filter_state['CANCELLED']==="true" || $filter_state['CANCELLED']===true)
                    $ticket_q->orWhere('state',State::CANCELLED);

            });
        } 

        $tickets->with('caterer');

        $tickets->orderByRaw(
            'CASE WHEN state='.State::PENDING.' THEN 1 ELSE 0 END DESC'
        )->orderByRaw(
            'CASE WHEN state='.State::CANCELLED.' THEN 0 ELSE 1 END DESC'
        )->orderByRaw(
            'CASE WHEN state='.State::CLOSED.' THEN 0 ELSE 1 END DESC'
        );

        
        return datatables($tickets)->rawColumns(['urgency','state','user_rating'])->toJson();
    }

    public function view($id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found on the system!');

        return view('sender.view',[
            'ticket'=>$ticket
        ]);
    }


    /**
     * CLOSE THE TICKET
     * @param $request Holds the rating and feedback from the user
     * @param $id The database id of the ticket
     * Condition: Can close if the ticket is solved
     *            Can close if the ticket is not cancelled
     *            Can close if the ticket is closed
     * 
     * @return json response
     */
    public function close(Request $request,$id)
    {
        $ticket=Ticket::find($id);
        abort_if($ticket==null,404,'Ticket could not be found!');
       
        abort_if($ticket->state==State::CLOSED,400,'Ticket was already closed.');
        abort_if($ticket->state!=State::SOLVED,400,'Ticket could not be closed until it was solved.');
        abort_if($ticket->state==State::CANCELLED,400,'Ticket is cancelled and could not be closed.');
        try {
            DB::beginTransaction();
            TicketActionHelper::close($ticket,$request['rating'],$request['feedback']);
            DB::commit();
            return response()->json(['message'=>'Ticket has been successfully closed!']);
            
        } catch (\Throwable $e) {
            DB::rollBack();
            abort(400,$e->getMessage());
        }
    }

    /**
     * CANCEL THE TICKET
     * @param $request Holds the cancellation reason
     * @param $control_number The unique control number of the ticket
     * @param $token The added security token for the ticket
     * Condition: Can cancel the ticket if the ticket is not closed
     *            Can cancel the ticket if the ticket is not yet cancelled
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
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and could not be cancelled anymore!');
        abort_if($ticket->state==State::CANCELLED,400,'Ticket was already cancelled!');

        try {
            
            DB::beginTransaction();
            
            TicketActionHelper::cancel($ticket,auth()->user(),$request['cancellation_reason']);

            DB::commit();

            return response()->json(['message'=>'Ticket has been successfully cancelled!']);

        } catch (\Throwable $e) {
            DB::rollBack();
            abort(400,$e->getMessage());
        }
    }


    public function save(Request $request)
    {
        $this->validate($request,[ 
            'sender_internet_protocol_address'=>'required',
            'sender_phone'=>'required',
            'title'=>'required|max:50',
            'content'=>'required',
            'urgency'=>'required',
        ]);
        
        try {

            # Get the authenticated user
            $user=auth()->user();

            DB::beginTransaction(); 

            $attachments=$request['attachments'];
            $carbon_copies=explode(',',$request['sender_carbon_copies']); 
            

            $ticket=TicketActionHelper::create(
                $user->name,
                $user->email,
                $carbon_copies,
                $request['sender_internet_protocol_address'],
                $request['sender_phone'],
                $user->factory,
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


    /**
     * Save custom application request
     * @param $request Holds the custom request
     */
    public function save_application(Request $request)
    {
        $this->validate($request,[
            'applications'=>'required',
        ]);

        try {

            DB::beginTransaction();

            $tickets=[];
            
            $user=auth()->user();

            # serialize to object
            $applications=ApplicationHelper::generalize_keys($request['applications']);

            # Get the selected applications
            foreach ($applications as $key => $value) {

                $app=$value->data;
                $application=CustomRequest::where('name',$app->name)->first();
                $application->applications=$app->applications;
                $application->fields=$app->fields;
                $ticket_info=ApplicationHelper::ticket_fields($application,$user);

                if(empty($app->selected_applications))
                {
                    DB::rollBack();
                    abort(400,'Please select at leas one system to be applied');
                }
                
                     

                # Get the sub applications
                foreach ($app->selected_applications as $app_name) { 
                    $tickets[]=TicketActionHelper::create(
                            $ticket_info->sender_name,
                            $ticket_info->sender_email,
                            null,
                            null,
                            $ticket_info->sender_phone,
                            $ticket_info->sender_factory,
                            $app_name,
                            ApplicationHelper::generate_field_format($application,$user,$app_name),
                            Urgency::NORMAL,
                            null
                    )->control_number;
                }
            }
  
            DB::commit();
            return response()->json([ 'message'=>'Application has been successfully submitted!']);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }

    }


    /**
     * OPEN THE TICKET AFTER UPDATED AS SOLVED BY THE SUPPORT STAFF
     * @param $control_number The unique control number of the ticket
     * @param $token The added security token for the ticket
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
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and could not be opened anymore!');
        abort_if($ticket->state==State::CANCELLED,400,'Ticket was already cancelled!');
        
        try {

            # Ticket must be solved first before it can be opened
            if($ticket->state==State::SOLVED)
            {
                DB::beginTransaction();
                
                TicketActionHelper::open($ticket,$request['reason']);

                DB::commit();
            }
            else
                abort(400,'Ticket must be solved first before it can be opened again!');

            return response()->json(['message'=>'Ticket has been successfully re opened!']);
        } catch (\Throwable $e) {
            DB::rollBack();
            abort(400,$e->getMessage());
        }
    }


}
