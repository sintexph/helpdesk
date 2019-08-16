<?php

namespace App\Helpers;
use App\Ticket;
use App\TicketStateProgress;
use State;

class TicketProgressHelper 
{
    public static function solved(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has solved the ticket';

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::SOLVED
        ]);
    }
    public static function created(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has created the ticket';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::PENDING
        ]);
    }

    public static function catered(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has catered the ticket';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::CATERED
        ]);
    }
    public static function processing(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has processing the ticket';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::PROCESSING
        ]);
    }
    public static function hold(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has put the ticket on-hold';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::HOLD
        ]);
    }

    public static function un_hold(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has un hold the ticket';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::UN_HOLD
        ]);
    }

    public static function closed(Ticket $ticket,$user_name,$auto_closed=false)
    {
        if($auto_closed===true)
            $content=strtolower(ucwords($user_name)).' has closed the ticket';
        else
            $content='System has has auto closed the ticket';

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::CLOSED
        ]);
    }
    public static function opened(Ticket $ticket,$user_name,$reason)
    {
        $content=strtolower(ucwords($user_name)).' has opened the ticket<br>Reason: '.$reason;
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::PROCESSING
        ]);
    }
    public static function cancelled(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has cancelled the ticket';
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::CANCELLED
        ]);
    }

    public static function custom_progress(Ticket $ticket,$user_name,$state)
    {
        $content=strtolower(ucwords($user_name)).' has updated the ticket to '.State::state($state);
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>$state,
        ]);
    }
    public static function escalate(Ticket $ticket,$escalated_from,$escalated_to)
    {
        if($escalated_from==$escalated_to)
            $content=$escalated_from.' has acquired the ticket';
        else
            $content=strtolower(ucwords($escalated_from)).' has escalated the ticket to '.strtolower(ucwords($escalated_to));
        
        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::ESCALATED
        ]);
    }

    public static function apply_approval(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has send the ticket for approval to '.$ticket->approver_name;

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::APPLIED_APPROVAL
        ]);
    }

    public static function approved(Ticket $ticket)
    {
        $content=strtolower(ucwords($ticket->approver_name)).' has approved the ticket';

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::APPROVED
        ]);
    }

    public static function cancel_approval(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' cancelled the approval of the ticket';

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::APPROVAL_CANCELLED
        ]);
    }
    public static function reject_approval(Ticket $ticket,$user_name)
    {
        $content=strtolower(ucwords($user_name)).' has rejected the ticket';

        TicketStateProgress::create([
            'content'=>$content,
            'ticket_id'=>$ticket->id,
            'state'=>State::REJECTED
        ]);
    }
}
