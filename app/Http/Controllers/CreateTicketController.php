<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use App\User;
use DB; 
use App\Helpers\TicketActionHelper;
use App\CustomRequest;
use App\Helpers\ApplicationHelper;
use App\Helpers\Urgency;
use State;

class CreateTicketController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('create-ticket');
    }


    /**
     * View the ticket
     */
    public function view($control_number,$token)
    {
        $ticket=Ticket::where('control_number','=',$control_number)->first();
        \abort_if($ticket==null || $ticket->token!=$token,404,'Ticket could not be found!');

        $state_progress=$ticket->state_progress()->orderBy('created_at','desc')->get();

        return view('home.ticket.view',[
            'ticket'=>$ticket,
            'state_progress'=>$state_progress,
        ]);
    }


    /**
     * CLOSE THE TICKET
     * @param $request Holds the rating and feedback from the user
     * @param $control_number The unique control number of the ticket
     * @param $token The added security token for the ticket
     * Condition: Can close if the ticket is solved
     *            Can close if the ticket is not cancelled
     *            Can close if the ticket is closed
     * 
     * @return json response
     */
    public function close(Request $request,$control_number,$token)
    {
        $ticket=Ticket::where('control_number',$control_number)->where('token',$token)->first();
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
    public function cancel(Request $request,$control_number,$token)
    {
        $this->validate($request,[
            'cancellation_reason'=>'required',
        ]);

        $ticket=Ticket::where('control_number',$control_number)->where('token',$token)->first();

        abort_if($ticket==null,404,'Ticket could not be found!');
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and could not be cancelled anymore!');
        abort_if($ticket->state==State::CANCELLED,400,'Ticket was already cancelled!');

        try {

            DB::beginTransaction();
            
            TicketActionHelper::cancel($ticket,'Sender',$request['cancellation_reason']);

            DB::commit();

            return response()->json(['message'=>'Ticket has been successfully cancelled!']);

        } catch (\Throwable $e) {

            DB::rollBack();
            abort(400,$e->getMessage());

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
    public function open(Request $request,$control_number,$token)
    {
        $this->validate($request,[
            'reason'=>'required',
        ]);
    
        $ticket=Ticket::where('control_number',$control_number)->where('token',$token)->first();
        abort_if($ticket==null,404,'Ticket could not be found!');
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and could not be cancelled anymore!');
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


    public function show_application(){
        return view('home.ticket.application'); 
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

            # serialize to object
            $applications=ApplicationHelper::generalize_keys($request['applications']);

            # Get the selected applications
            foreach ($applications as $key => $value) {

                $app=$value->data;
                $application=CustomRequest::where('name',$app->name)->first();
                $application->applications=$app->applications;
                $application->fields=$app->fields;
                $ticket_info=ApplicationHelper::ticket_fields($application);
                
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
                            ApplicationHelper::generate_field_format($application,$app_name),
                            Urgency::NORMAL,
                            null
                    )->control_number;
                }
            }

            DB::commit();

            return response()->json([ 'message'=>'Application has been successfully submitted!', ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            abort(400,$th->getMessage());
        }

    } 
}
