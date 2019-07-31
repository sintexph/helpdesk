<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\TicketConversation;
use App\User;
use App\Helpers\ContentHelper;
use App\TicketConversationAttachment;

class ConversationHelper 
{
    
    /**
     * Extract the images/pictures from the content and upload it to storage and replace the embedded images source to the uploaded image url source
     * @param $ticket The helpdesk ticket
     * @param $content
     */
    public static function extract_images_content(TicketConversation $ticket_conversation)
    {
        $embedded=ContentHelper::image_embedded_upload($ticket_conversation->created_by,$ticket_conversation->content);

        # Remove unnecessary tags and break lines
        $content=ltrim($embedded['content'], "<html>");
        $content=rtrim($embedded['content'], "</html>");
        $content=ltrim($content, "\n"); 


        $ticket_conversation->content=$embedded['content'];
        $ticket_conversation->save();

        foreach($embedded['file_uploads'] as $file_upload)
        {
            TicketConversationAttachment::create([
                'file_upload_id'=>$file_upload->id,
                'ticket_conversation_id'=>$ticket_conversation->id,
            ]);
        }
        return $ticket_conversation;
    }
}
