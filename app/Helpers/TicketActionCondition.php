<?php

namespace App\Helpers;
 
use App\Ticket; 
use State;
use App\User;

trait TicketActionCondition 
{
    /**
     * CHECK IF THE TICKET IS CANCELLED
     */
    function check_cancelled(Ticket $ticket)
    {
       abort_if($ticket->state==State::CANCELLED,400,'Ticket was already cancelled and could not any further action!');
    }
    function check_applied_for_approval(Ticket $ticket)
    {
       abort_if($ticket->state==State::APPLIED_APPROVAL,400,'You cannot do any further action if the ticket is on the state of approval!');
    }
    function check_solved(Ticket $ticket)
    {
       abort_if($ticket->state==State::SOLVED,400,'Ticket was already solved and could not any further action!');
    }
    function check_hold(Ticket $ticket)
    {
        abort_if($ticket->state==State::HOLD,400,'Ticket was still on hold and could not do any further action!');
    }

    function general_condition(Ticket $ticket)
    {
        abort_if($ticket->state==State::CLOSED,400,'Ticket was already closed and could not do any further action!');
        abort_if($ticket->state==State::CANCELLED,400,'Ticket was already cancelled and could not do any further action!');
        $this->check_cancelled($ticket);
        $this->check_solved($ticket);
        $this->check_applied_for_approval($ticket);
    }
    

    /**
     *  CAN 
     *      SOLVE IF THE TICKET IS PROCESSING
     *  CANNOT 
     *      SOLVE IF SOLVE ALREADY
     *      CANCELLED  
     *      APPLIED FOR APPROVAL
     */
    function condition_solve(Ticket $ticket)
    {
        $this->check_cancelled($ticket);
        $this->check_solved($ticket);
        $this->check_applied_for_approval($ticket);
        $this->check_hold($ticket);
        
        abort_if($ticket->state==State::PENDING || $ticket->state==State::CATERED,400,'Please process the ticket first before updating it to solved!');

    }

    /**
     *  CAN HOLD THE TICKET IF NOT SOLVED YET
     *  CAN HOLD THE TICKET IF IS PROCESSING OR CATERED
     */
    function condition_hold(Ticket $ticket)
    {
        $this->general_condition($ticket);
        abort_if($ticket->state==State::HOLD,400,'Ticket was already put on hold');
        abort_if($ticket->state!=State::PROCESSING && $ticket->state!=State::CATERED,400,'Please process the ticket first before you can put it on hold!');
    }
    

    /**
     *  CAN CATER ONLY OF THE STATUS OF THE TICKET IS PENDING
     *  CANNOT CATER IF CATERED
     */
    function condition_cater(Ticket $ticket)
    {
        $this->check_cancelled($ticket);
        abort_if($ticket->state==State::CATERED,400,'Ticket was already catered!');
    }


    /**
     *  CAN PROCESS IF THE TICKET STATE IS CATERED
     *  CANNOT PROCESS IF PROCESSED
     */
    function condition_process(Ticket $ticket)
    {
        $this->check_cancelled($ticket);
        abort_if($ticket->state==State::PROCESSING,400,'Ticket was already processing!');
        abort_if($ticket->state!=State::CATERED,400,'Ticket must be catered first before processing!');
    }
    
    /**
     * CAN UN HOLD THE TICKET IT WAS HOLD
     */
    function condition_un_hold(Ticket $ticket)
    {
        abort_if($ticket->state!=State::HOLD,400,'Hold the ticket first before un-holding!');
    }
    
    /**
     *  CAN
     *      IF NOT CANCELLED
     *      IF NOT SOLVED
     *      IF NOT APPLIED FOR APPROVAL
     */
    function condition_cancel(Ticket $ticket)
    {
        $this->check_cancelled($ticket);
        $this->check_solved($ticket);
        $this->check_applied_for_approval($ticket);
    }
    
    /**
     *  CANNOT OPEN IF THE TICKET IS CANCELLED
     *  CANNOT OPEN IF THE TICKET IS CLOSED
     *  CAN OPEN ONLY IF THE TICKET IF IT IS SOLVED
     */
    function condition_open(Ticket $ticket)
    {
        abort_if($ticket->state==State::CLOSED,400,'The ticket was already closed and could not be opened anymore!');
        abort_if($ticket->state==State::CANCELLED,400,'The ticket was already cancelled and could not be opened anymore!');
        abort_if($ticket->state!=State::SOLVED,400,'The ticket was already solved and could not be opened anymore!');
        
    }
    
    /**
     *  CANNOT 
     *      CANCELLED
     *      SOLVED
     *      CLOSED
     *      TICKET IS NOT CATERED
     *      NOT THE AUTHENTICATED USER IS THE CATERER
     */
    function condition_escalate(Ticket $ticket,User $escalate_to)
    {
        if($ticket->state==State::PENDING)
            abort(400,'Ticket must be catered first before escalating!');

        $this->general_condition($ticket);

        abort_if($escalate_to==null,404,'Support staff selected could not be found on the system!');
        abort_if($escalate_to->id==$ticket->catered_by,400,'You mus select different support staff!');
            
    }
    
    /**
     *  Could not add reference if ticket is cancelled, solved, or closed and pending
     */
    function condition_add_reference(Ticket $ticket,Ticket $reference)
    {
        
        if($ticket->state==State::PENDING)
            abort(400,'Ticket must be catered first before adding reference!');
        elseif($ticket->state==State::SOLVED)
            abort(400,'The ticket was already solved and could not add reference anymore!');
        elseif($ticket->state==State::CLOSED)
            abort(400,'The ticket was already closed and could not add reference anymore!');
        elseif($ticket->state==State::CANCELLED)
            abort(400,'The ticket was already cancelled and could not add reference anymore!');
        
        abort_if(TicketReference::where('ticket_reference_id',$reference->id)->exists(),400,'The ticket is already added as reference!');
            
    }

    /**
     *  Could not apply if already applied and ticket is cancelled, solved, or closed and pending
     */
    function condition_apply_approval(Ticket $ticket)
    {
        
        if($ticket->state==State::PENDING)
            abort(400,'Ticket must be catered first before it can be applied for approval!');
        elseif($ticket->state==State::SOLVED)
            abort(400,'The ticket was already solved and could not apply approval anymore!');
        elseif($ticket->state==State::CLOSED)
            abort(400,'The ticket was already closed and could not apply approval anymore!');
        elseif($ticket->state==State::CANCELLED)
            abort(400,'The ticket was already cancelled and could not apply approval anymore!');
        
        if($ticket->approver_name!==null)
            abort(400,'The ticket was already applied for approval!');
        elseif($ticket->approved_at!==null)
            abort(400,'The ticket was already approved!');
            
    }
    
    /**
     *  COULD NOT APPLY IF ALREADY APPLIED AND TICKET IS CANCELLED, SOLVED, OR CLOSED AND PENDING
     */
    function condition_cancel_approval(Ticket $ticket)
    {
        if($ticket->state==State::PENDING)
            abort(400,'Ticket must be catered first!');
        elseif($ticket->state==State::SOLVED)
            abort(400,'The ticket was already solved!');
        elseif($ticket->state==State::CLOSED)
            abort(400,'The ticket was already closed!');
        elseif($ticket->state==State::CANCELLED)
            abort(400,'The ticket was already cancelled!');
        
        if($ticket->approver_name===null)
            abort(400,'Cancellation could not be executed since there has no approval applied!');
        elseif($ticket->approved_at!==null)
            abort(400,'The ticket was already approved and could not be cancelled anymore!');   
    }
    /**
     *  COULD NOT APPLY IF ALREADY APPLIED AND TICKET IS CANCELLED, SOLVED, OR CLOSED AND PENDING
     */
    function condition_resend_approval(Ticket $ticket)
    {
        if($ticket->state==State::PENDING)
            abort(400,'Ticket must be catered first!');
        elseif($ticket->state==State::SOLVED)
            abort(400,'The ticket was already solved!');
        elseif($ticket->state==State::CLOSED)
            abort(400,'The ticket was already closed!');
        elseif($ticket->state==State::CANCELLED)
            abort(400,'The ticket was already cancelled!');
        
        if($ticket->approver_name===null)
            abort(400,'Resend could not be executed since there has no approval applied!');
        elseif($ticket->approved_at!==null)
            abort(400,'The ticket was already approved and could not be resend anymore!');   
    }

    /**
     * TICKET MUST BE PROCESSED BEFORE CAN SET ANY CUSTOM ACTION
     */
    function condition_custom_progress(Ticket $ticket)
    {
        if($ticket->state==State::PENDING || $ticket->state==State::CATERED)
            abort(400,'You must process the ticket first before can set a custom progress!');
        
        $this->general_condition($ticket);
    }
}
