<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ticket;
use PDF;

class ContentController extends Controller
{
    public function view($control_number,$token)
    {
        $ticket=Ticket::where('control_number','=',$control_number)->first();
        \abort_if($ticket==null || $ticket->token!=$token,404,'Ticket could not be found!');

        return view('layouts.content',['ticket'=>$ticket]);
    }

    public function download($control_number)
    {
        $ticket=Ticket::where('control_number','=',$control_number)->first();
        \abort_if($ticket==null,404,'Ticket could not be found!');
        
        $template=view('layouts.content',['ticket'=>$ticket,'download'=>true]);
        $pdf = \PDF::loadHTML($template)->setPaper('letter')->setOrientation('portrait');
        $pdf->setOption('dpi',300); 
        $pdf->setOption('margin-top',20);
        $pdf->setOption('margin-right',20);
        $pdf->setOption('margin-bottom',20);
        $pdf->setOption('margin-left',20);
        
        return $pdf->download('ticket.no.'.$ticket->control_number.'.pdf'); 
    }

 

}
