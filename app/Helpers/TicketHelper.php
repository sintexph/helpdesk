<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\Ticket;
use App\User;
use App\TicketAttachment;
use App\Helpers\ContentHelper;
use TicketState;
use App\Setting;

class TicketHelper 
{
    public static function control_number()
    {
        $ticket=Ticket::orderBy('id','desc')->first();
        if($ticket==null)
            return static::control_number_format(1);
        else
            return static::control_number_format($ticket->id+1);
    }

    public static function control_number_format($id)
    {
        return sprintf("%'.09d",$id);
    }

    /**
     * Extract the images/pictures from the content and upload it to storage and replace the embedded images source to the uploaded image url source
     * @param $ticket The helpdesk ticket
     * @param $content
     */
    public static function extract_images_content(Ticket $ticket,User $user=null)
    {
        $embedded=ContentHelper::image_embedded_upload($user==null?'Sender':$user->name,$ticket->content);
        $ticket->content=$embedded['content'];
        $ticket->save();

        foreach($embedded['file_uploads'] as $file_upload)
        {
            TicketAttachment::create([
                'file_upload_id'=>$file_upload->id,
                'ticket_id'=>$ticket->id,
            ]);
        }
        return $ticket;
    }


    public static function ticket_number_state(Ticket $ticket)
    {
        $state=0;

        if($ticket->cancelled_at!=null)
            $state=TicketState::CANCELLED;
        elseif($ticket->hold_at!=null && $ticket->un_hold_at==null)
            $state=TicketState::HOLD;
        elseif($ticket->closed_at!=null)
            $state=TicketState::CLOSED;
        elseif($ticket->solved_at!=null)
            $state=TicketState::SOLVED;
        elseif($ticket->processing_at!=null)
            $state=TicketState::PROCESSING;            
        elseif($ticket->catered_at!=null)
            $state=TicketState::CATERED;
        elseif($ticket->catered_at==null)
            $state=TicketState::PENDING;
 
        return $state;
    }


    public static function auto_close_tickets()
    {
        $auto_close_setting=Setting::where('name','AUTO_CLOSE_TICKET')->first();

        $tickets=Ticket::where('state',TicketState::SOLVED)->get()->filter(function($ticket)use($auto_close_setting){
            $cd=\Carbon\Carbon::now();
            $dc=new \Carbon\Carbon($ticket->created_at);

            # Get the day difference between two dates
            $day_diff=(int)($cd->diffInSeconds($dc)/86400); 

            # return if the day difference is bigger or equals to the aged settings value
            if($day_diff>=(int)$auto_close_setting->value)
                return $ticket; 
        });

        
        foreach ($tickets as $ticket) {
            TicketActionHelper::close($ticket,null,null,true);
        }

        return $tickets;
    }
    
}
