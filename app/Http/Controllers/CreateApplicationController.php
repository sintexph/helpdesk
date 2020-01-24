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

class CreateApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('create-application');
    }
    
    /**
     * Save custom application request
     * @param $request Holds the custom request
     */
    public function save(Request $request)
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
                            null,
                            auth()->user()
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
