<?php

namespace App\Helpers;

use App\Ticket;
use App\User;
use App\Helpers\MailSendingHelper as MailSender;
use App\Helpers\TicketProgressHelper;
use App\Helpers\UploadHelper;
use App\TicketAttachment;
use App\TicketNote;
use App\Helpers\Metrics;
use State;

class TicketActionHelper 
{
    public static function cater(Ticket $ticket,User $user,$processing)
    {
        $ticket->state=State::CATERED;
        $ticket->catered_by=$user->id;
        $ticket->save();


        $target=$ticket->targets()->where('target',State::CATERED)->first();
        $target->completed_date=\Carbon\Carbon::now();
        $target->assigned_to=$user->id;
        $target->save();

        # Send email
        MailSender::cater_ticket($ticket);
        # Save ticket progress history
        TicketProgressHelper::catered($ticket,$user->name);

        if($processing===true)
            static::processing($ticket,$user,true);

        return $ticket;

    }

    /**
     * CLOSE THE TICKET
     * @param $ticket The helpdesk ticket
     * @param $rating The user rating
     * @param $feedback The user feedback
     * @param $auto_close System auto closing function by default false
     */
    public static function close(Ticket $ticket,$rating,$feedback,$auto_close=false)
    {
        $ticket->state=State::CLOSED;
        $ticket->closed_at=\Carbon\Carbon::now(); # to be removed


        $target=$ticket->targets()->where('target',State::CLOSED)->first();
        $target->completed_date=\Carbon\Carbon::now();
        $target->assigned_to=$ticket->catered_by;
        $target->save();
        
        # Check if there is a rating then record
        if(!empty($rating))
            $ticket->user_rating=$rating;

        # Check if there is a feedback then record
        if(!empty($feedback))
            $ticket->user_feedback=$feedback;
            
        $ticket->save();

        if($auto_close===false)
        {
            MailSendingHelper::close_ticket($ticket);
        }

        TicketProgressHelper::closed($ticket,$ticket->sender_name);


        return $ticket;
    }
    public static function open(Ticket $ticket,User $user,$reason)
    {
        $ticket->state=State::PROCESSING;
        $ticket->solved_at=null; # to be removed
        $ticket->category=null;
        $ticket->solution=null;
        $ticket->save();

        $target=$ticket->targets()->where('target',State::SOLVED)->first();
        $target->completed_date=null;
        $target->assigned_to=null;
        $target->save();

        # Save ticket progress history
        TicketProgressHelper::opened($ticket,$user->name,$reason);

        return $ticket;
    }

    /**
     * CANCEL THE TICKET
     * @param $ticket to be cancelled
     * @param $user who cancelled the ticket
     * @param $reason what is the reason for cancelling the ticket
     */
    public static function cancel(Ticket $ticket,User $user,$reason)
    {
        $ticket->state=State::CANCELLED;
        $ticket->cancelled_at=\Carbon\Carbon::now();
        $ticket->cancelled_by=$user->name;
        $ticket->cancellation_reason=$reason;
        $ticket->save();

        MailSendingHelper::cancel_ticket($ticket,$user);

        # Save ticket progress history
        TicketProgressHelper::cancelled($ticket,$user->name);

        return $ticket;
    }

    /**
     * SOLVE THE TICKET
     * @param $ticket to be solved
     * @param $user who solved the ticket
     * @param $category Category of the ticket
     * @param $solution Action taken
     */
    public static function solve(Ticket $ticket,User $user,$category,$solution,$message_to_sender=false,$message=null)
    {
        $ticket->state=State::SOLVED;
        $ticket->solved_at=\Carbon\Carbon::now();  # to be removed
        $ticket->category=$category;
        $ticket->solution=$solution;
        $ticket->save();

        $target=$ticket->targets()->where('target',State::SOLVED)->first();
        $target->completed_date=\Carbon\Carbon::now();
        $target->assigned_to=$user->id;
        $target->save();

        # Send email
        MailSendingHelper::solve_ticket($ticket);

        if($message_to_sender==true)
            MailSendingHelper::send_email($ticket,$user,$message);

        # Save ticket progress history
        TicketProgressHelper::solved($ticket,$user->name);

        return $ticket;
    }

    public static function hold(Ticket $ticket,User $user)
    {
        $ticket->state=State::HOLD;
        $ticket->hold_at=\Carbon\Carbon::now();
        $ticket->un_hold_at=null;
        $ticket->save();
        
        MailSender::hold_ticket($ticket);


        # Save ticket progress history
        TicketProgressHelper::hold($ticket,$user->name);

        return $ticket;
    }
    public static function un_hold(Ticket $ticket,User $user)
    {
        if(empty($ticket->processing_at))
            $ticket->state=State::CATERED;
        else
            $ticket->state=State::PROCESSING;

        $ticket->un_hold_at=\Carbon\Carbon::now();
        $ticket->save();

        # Save ticket progress history
        TicketProgressHelper::un_hold($ticket,$user->name);

        return $ticket;
    }
    public static function processing(Ticket $ticket,User $user,$send_email=true)
    {
        $ticket->state=State::PROCESSING;
        $ticket->processing_at=\Carbon\Carbon::now();  # to be removed
        $ticket->save();

        $target=$ticket->targets()->where('target',State::PROCESSING)->first();
        $target->completed_date=\Carbon\Carbon::now();
        $target->assigned_to=$user->id;
        $target->save();

        # Save ticket progress history
        TicketProgressHelper::processing($ticket,$user->name);
        
        if($send_email==true)
            MailSender::process_ticket($ticket);

        return $ticket;
    }

    public static function apply_approval(Ticket $ticket,User $user,$name,$email)
    {
        $ticket->approver_name=$name;
        $ticket->approver_email=$email;
        $ticket->state=State::APPLIED_APPROVAL;
        $ticket->save();

        # Send Approval
        MailSendingHelper::apply_approval($ticket);
        
        # Save ticket progress history
        TicketProgressHelper::apply_approval($ticket,$user->name);

        return $ticket;
    }

    public static function resend_approval(Ticket $ticket,User $user)
    {
        # Send Approval
        MailSendingHelper::apply_approval($ticket);
        
        # Add note for resending the approval
        TicketNote::create([
            'content'=>'Resend notification to '.strtolower(ucwords($ticket->approver_name)).' for ticket approval.',
            'created_by'=>$user->name,
            'ticket_id'=>$ticket->id,
        ]);

        return $ticket;
    }

    public static function cancel_approval(Ticket $ticket,User $user)
    {
        $ticket->state=State::PROCESSING;
        $ticket->approver_name=null;
        $ticket->approver_email=null;
        $ticket->save();
    
        # Save ticket progress history
        TicketProgressHelper::cancel_approval($ticket,$user->name);

        return $ticket;
    }

    /**
     * Custom Progress
     */
    public static function custom_progress(Ticket $ticket,User $user,$state)
    {
        # Save ticket progress history
        TicketProgressHelper::custom_progress($ticket,$user->name,$state);

        # Send email
        MailSender::custom_progress($ticket);

        return $ticket;
    }

    public static function reject_approval(Ticket $ticket)
    {
        $ticket->state=State::PROCESSING;
        $ticket->approver_name=null;
        $ticket->approver_email=null;
        $ticket->save();
    
        # Save ticket progress history
        TicketProgressHelper::reject_approval($ticket);

        return $ticket;
    }
    public static function approved(Ticket $ticket)
    {
        $ticket->state=$ticket->last_state;
        $ticket->approved_at=\Carbon\Carbon::now();
        $ticket->save();
    
        # Save ticket progress history
        TicketProgressHelper::approved($ticket);

        return $ticket;
    }

    public static function escalate(Ticket $ticket,User $escalate_from,User $escalate_to)
    {
        
        $ticket->catered_by=$escalate_to->id; 
        $ticket->escalated=true;
        $ticket->save();

        # Send email
        MailSender::escalate_ticket($ticket,$escalate_from,$escalate_to);

        # Save ticket progress history
        TicketProgressHelper::escalate($ticket,$escalate_from->name,$escalate_to->name);

        return $ticket;
    }

    /**
     * @param $ticket Ticket to modify
     * @param $user Who will modify
     * @param $cc The carbon copies to update
     */
    public static function modify_carbon_copies(Ticket $ticket,User $user,Array $cc)
    {
        $cc=array_filter($cc);
        $old = $ticket->sender_carbon_copies;
        
        $added_emails = array_filter(array_diff($cc, $old)); // Get the added emails and strip out the empty values
        $removed_emails = array_filter(array_diff($old, $cc)); // Get the remove emails and strip out the empty values

        # Add note for modifying the carbon copies
        if(!empty($removed_emails))
        {
            $content='Removed carbon copy '.implode(", ",$removed_emails);
            TicketNote::create([
                'content'=>$content,
                'created_by'=>$user->name,
                'ticket_id'=>$ticket->id,
            ]);
        }
 
        if(!empty($added_emails))
        {
            $content='Added carbon copy '.implode(", ",$added_emails);
            TicketNote::create([
                'content'=>$content,
                'created_by'=>$user->name,
                'ticket_id'=>$ticket->id,
            ]);
        }

        $ticket->sender_carbon_copies=$cc;
        $ticket->save();

        return $ticket;
    }


    /**
     * @param $ticket Ticket to modify
     * @param $user Who will modify
     * @param $cc The carbon copies to update
     */
    public static function change_sender(Ticket $ticket,User $user,User $sender)
    {
        $ticket->sender_name=$sender->name; 
        $ticket->sender_email=$sender->email;
        $ticket->sender_factory=$sender->factory;
        $ticket->save();

        # record progress
        TicketProgressHelper::change_sender($ticket,$user,$sender);

        return $ticket;
    }


    /**
     * Save ticket in the database
     * @param $sender_name The name of the sender
     * @param $sender_email The email of the sender
     * @param $carbon_copies Can receive the ticket = @nullable
     * @param $sender_internet_protocol_address The ip address of the user
     * @param $sender_phone The contact of the sender
     * @param $sender_factory The factory of the sender  = @nullable
     * @param $title The ticket title/head
     * @param $content The details of the ticket
     * @param $urgency The urgency of the ticket
     * @param $attachments The attachment/files of the ticket = @nullable
     */
    public static function create(
        $sender_name,
        $sender_email,
        $carbon_copies,
        $sender_internet_protocol_address,
        $sender_phone,
        $sender_factory,
        $title,
        $content,
        $urgency,
        $attachments,
        User $creator
    )
    {
        $ticket=new Ticket;
                    
        $ticket->control_number=TicketHelper::control_number();
        $ticket->token=str_random(32);
        $ticket->sender_name=$sender_name;
        $ticket->sender_email=$sender_email;
 
        if(!empty($carbon_copies))
            $ticket->sender_carbon_copies=$carbon_copies;

        if(!empty($sender_internet_protocol_address))
            $ticket->sender_internet_protocol_address='User: '.$sender_internet_protocol_address.' | IRA: '.\Request::ip();
        else
            $ticket->sender_internet_protocol_address='IRA: '.\Request::ip();

        $ticket->sender_phone=$sender_phone;
        $ticket->sender_factory=$sender_factory;

        $ticket->title=$title;
        $ticket->content=$content;

   
        $ticket->urgency=$urgency;
        $ticket->save();
          
        # Upload the embedded images of the content
        TicketHelper::extract_images_content($ticket);
 

        if(!empty($attachments))
        {
            # Upload the attachments
            foreach($attachments as $attachment)
            {
                $file_upload=UploadHelper::upload_file($attachment,$ticket->sender_name);
                TicketAttachment::create([
                    'file_upload_id'=>$file_upload->id,
                    'ticket_id'=>$ticket->id,
                ]);
            }
        }


        # Send email to all Support Staff
        foreach (User::where('role','<>',UserRole::SENDER)->where('factory',$ticket->sender_factory)->get() as $user) {
            # Send email
            MailSendingHelper::new_ticket($ticket,$user);
        }


        # Save ticket state progress history
        TicketProgressHelper::created($ticket,$creator->name);

        return $ticket;
    }


    /**
     * ADD NOTE TO THE TICKET
     * @param $note The note content
     * @param $ticket The ticket that will be added a note
     * @param $user who will add the ticket
     * @return Note Instance
     */
    public static function add_note($note,Ticket $ticket,User $user)
    {
        $ticket_note=TicketNote::create([
            'content'=>$note,
            'created_by'=>$user->name,
            'ticket_id'=>$ticket->id,
        ]);

        # Send a notification to the caterer of the ticket
        # Do not send notification if the adder of the note is the caterer
        if($user->state!=State::PENDING && $user->id!=$ticket->catered_by)
        {
            MailSendingHelper::add_note($ticket_note,$user); 
        }
        
        return $ticket_note;
    }
}
