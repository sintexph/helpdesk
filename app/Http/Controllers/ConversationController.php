<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TicketConversation;
use App\Ticket;
use App\Helpers\ConversationHelper;
use App\TicketConversationAttachment;
use DB;
use App\User;
use App\Helpers\UploadHelper;
use App\Helpers\MailSendingHelper as MailSender;
use App\Helpers\TicketState;

class ConversationController extends Controller
{
    /**
     * List of conversations
     */
    public function conversations($id,$token)
    {
        $ticket=Ticket::find($id);
        if(empty($ticket) || $ticket->token!=$token)
            return json_encode([]);
        else
        {
            $conversations=$ticket->conversations();
            $conversations->with(['attachments'=>function($attachment){
                $attachment->select('*')->with('file_upload');
            }]);
            $conversations=$conversations->orderBy('id','desc')
            ->with('creator')
            ->get();
            return json_encode($conversations);
        }
    }

    public function save(Request $request,$id)
    {
        $this->validate($request,[
            'conversation'=>'required',
        ]);
        $attachments=$request['attachments'];

        $ticket=Ticket::find($id);
        \abort_if($ticket==null,404,'Could not find the ticket!');

        try {
            DB::beginTransaction();
            
            $conversation=new TicketConversation;
            $conversation->ticket_id=$ticket->id;
            $conversation->content=$request['conversation'];
            $conversation->created_by=auth()->user()->id;

            # Upload the embedded images from the content
            ConversationHelper::extract_images_content($conversation);

            
            if(!empty($attachments))
            {
                # Upload the attachments
                foreach($attachments as $attachment)
                {
                    $file_upload=UploadHelper::upload_file($attachment,$conversation->created_by);
                    TicketConversationAttachment::create([
                        'file_upload_id'=>$file_upload->id,
                        'ticket_conversation_id'=>$conversation->id,
                    ]);
                }
            }
            $conversation->save();

            # Get all the creator ids of the conversation of the ticket so they can be set as receiver
            $user_ids=TicketConversation::select(['created_by'])->where('ticket_id',$ticket->id)->groupBy('id')->get();
            $user_ids=$user_ids->map(function($u){
                return $u->created_by;
            });

            # Add the caterer id as receiver
            if($ticket->state!=TicketState::PENDING)
                $user_ids[]=$ticket->catered_by;

            # Get all the users based on the $user_ids
            $users=User::whereIn('id',$user_ids)->get();
            foreach ($users as $user) {
                # Send email
                MailSender::conversation($conversation,$user);
            }


            DB::commit();  

            return response()->json(['message'=>'Conversation has been successfully saved!']);
        }
        catch (\Exception $e) {
            DB::rollBack();
            abort(400,$e->getMessage());
        } 

    }

    public function delete($id)
    {
        $conversation=TicketConversation::find($id);
        \abort_if($conversation==null,404,'Conversation is not exists!');
        $conversation->delete();

        return response()->json(['message'=>'Conversation has been successfully deleted!']);
    }
}


