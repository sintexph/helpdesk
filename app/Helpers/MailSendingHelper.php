<?php

namespace App\Helpers;
use App\TicketConversation;
use App\Ticket;
use App\User;
use App\TicketNote;

use App\Mail\ReplyConversationMailable;
use App\Mail\TicketHoldMailable;
use App\Mail\TicketCaterMailable;
use App\Mail\NewTicketMailable;
use App\Mail\TicketProcessingMailable;
use App\Mail\TicketCancelledMailable;

use App\Mail\Escalate\SendToSupportMailable;
use App\Mail\Escalate\SendToSenderMailable;

use App\Mail\TicketSolveMailable;
use App\Mail\TicketCloseMailable;
use App\Mail\ForApprovalMailable;
use App\Mail\AddNoteMailable;
use App\Mail\UpdatedProgressMailable;
use App\Mail\SendEmailMailable;

class MailSendingHelper 
{
    private static function send($to,$mailable,$cc=null)
    {
        if(!empty($cc))
        {
            \Mail::to($to)
            ->cc($cc)
            ->queue($mailable);
        }
        else {
            \Mail::to($to)
            ->queue($mailable);
        }
    }
    
    public static function send_email(Ticket $ticket,User $user,$content)
    {
        static::send($ticket->sender_email,new SendEmailMailable($ticket,$user,$content,'Message from '.$user->name),$ticket->sender_carbon_copies);
    }


    /**
     * SEND CONVERSATION TO USER
     * @param $ticket_conversation The message
     * @param $user Receiver of the message
     */
    public static function conversation(TicketConversation $ticket_conversation,User $user)
    {
        static::send($user->email,new ReplyConversationMailable($ticket_conversation),$ticket_conversation->ticket->sender_carbon_copies);
    }

    /**
     * SEND EMAIL TO SENDER OF THE TICKET
     */
    public static function hold_ticket(Ticket $ticket)
    {
        static::send($ticket->sender_email,new TicketHoldMailable($ticket),$ticket->sender_carbon_copies);
    }

    /**
     * SEND EMAIL TO SENDER OF THE TICKET
     */
    public static function cater_ticket(Ticket $ticket)
    {
        static::send($ticket->sender_email,new TicketCaterMailable($ticket),$ticket->sender_carbon_copies);
    }

    /**
     * SEND EMAIL TO SENDER OF THE TICKET
     */
    public static function process_ticket(Ticket $ticket)
    {
        static::send($ticket->sender_email,new TicketProcessingMailable($ticket),$ticket->sender_carbon_copies);
    }

    /**
     * SEND EMAIL TO CATERER or SENDER OF THE TICKET
     */
    public static function cancel_ticket(Ticket $ticket,User $user)
    {
        $receiver_name=$user->name;
        static::send($user->email,new TicketCancelledMailable($ticket,$receiver_name),$ticket->sender_carbon_copies);
    }


    /**
     * NOTIFY PEOPLE THAT THE TICKET WAS ESCALATED
     */
    public static function escalate_ticket(Ticket $ticket,User $escalated_by,User $escalated_to)
    {
        # send email to the sender that his/her ticket was escalated
        static::send($ticket->sender_email,new SendToSenderMailable($ticket,$escalated_by->name,$escalated_to->name),$ticket->sender_carbon_copies);

        # send email to the person who will be escalated
        static::send($escalated_to->email,new SendToSupportMailable($ticket,$escalated_by->name,$escalated_to->name),$ticket->sender_carbon_copies);
    }
    

    /**
     * SEND EMAIL TO THE SUPPORT THAT THERE IS A NEWLY CREATED TICKET
     */
    public static function new_ticket(Ticket $ticket,User $support)
    {
        static::send($support->email,new NewTicketMailable($ticket,$support));
    }

    /**
     * SEND EMAIL TO SENDER OF THE TICKET
     */
    public static function solve_ticket(Ticket $ticket)
    {
        static::send($ticket->sender_email,new TicketSolveMailable($ticket));
    }

    /**
     * SEND EMAIL TO THE CATERER
     */
    public static function close_ticket(Ticket $ticket)
    {
        static::send($ticket->caterer->email,new TicketCloseMailable($ticket),$ticket->sender_carbon_copies);
    }

    /**
     * SEND EMAIL TO THE APPROVER OF THE TICKET
     */
    public static function apply_approval(Ticket $ticket)
    {
        static::send($ticket->approver_email,new ForApprovalMailable($ticket));
    }

    /**
     * SEND EMAIL TO CATERER OF THE TICKET THAT SOMEONE ADDED A NOTE
     */
    public static function add_note(TicketNote $ticket_note,User $user)
    {
        static::send($ticket_note->ticket->caterer->email,new AddNoteMailable($ticket_note),$ticket_note->ticket->sender_carbon_copies);
    }
    
    /**
     * SEND EMAIL TO THE REQUESTOR THAT THERE IS AN UPDATE WITH THE PROGRESS
     */
    public static function custom_progress(Ticket $ticket)
    {
        static::send($ticket->sender_email,new UpdatedProgressMailable($ticket),$ticket->sender_carbon_copies);
    }
}
