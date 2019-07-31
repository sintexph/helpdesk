<?php

namespace App\Helpers\Email;
use App\Ticket;
use Ddeboer\Imap\Server;
use App\Helpers\UploadHelper;
use App\TicketConversation;

/**
 * Get the replies of the user for a specific ticket from email and save it the database as conversation
 */
class ImapConversationHelper 
{
    public static function init()
    {
        $server = new Server(
            '172.28.48.25', // required
            '143',     // defaults to '993'
            'notls'
        );

        $connection = $server->authenticate('no-reply@sportscity.com.ph', '123asd!@#');

        $mailbox = $connection->getMailbox('INBOX');
        $messages = $mailbox->getMessages();

        foreach ($messages as $message) {

            $subject=$message->getSubject();
                    
            # Check if the subject ticket has a ticket
            if(TicketExistenceHelper::check_valid_ticket_conversation($subject))
            {
                $control_number=TicketExistenceHelper::extract_control_number($subject);
                $ticket=Ticket::where('control_number','=',$control_number)->first();

                # if the ticket is exists
                if($ticket!=null)
                {
                    $body=$message->getBodyHtml();
                    $from=$message->getFrom();

                    $attachments = $message->getAttachments();

                    foreach ($attachments as $attachment) {

                        $mime=$attachment->getSubType();
                        $type=$attachment->getType();
                        $decoded_file=$attachment->getDecodedContent();
                        $file_name=$attachment->getFilename();
                        $size=$attachment->getBytes();
                        $uploader=$from->getName();

                        # Upload the attachments to the server
                        $file_upload=UploadHelper::upload_decoded_file($uploader,$decoded_file,$file_name,$type,$mime,$size);

                        # get the structure of the attachment
                        $structure=$attachment->getStructure();
                        if($structure->ifid==1)
                        {
                            # Change the path of the embedded image of the email
                            $attachment_id=substr($structure->id, 1, -1);
                            $body=str_replace("cid:".$attachment_id,$file_upload->url,$body);
                        }
                    }

                    # Save the conversation
                    $conversation=new TicketConversation;
                    $conversation->ticket_id=$ticket->id;
                    $conversation->content=$body;
                    $conversation->created_by=$from->getName();
                    $conversation->save();
                        
                    $message->delete();
                }
            }
        }

        $connection->expunge();
    }
}
