<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\Ticket;
use App\User;
use App\TicketAttachment;


class ContentHelper 
{
    public static function image_embedded_upload($uploader,$content)
    {
        $result=[
            'file_uploads'=>[],
            'content'=>$content,
        ];
        
        # Get the embedded images to the content 
        $doc = new \DOMDocument();
        # suppress the warning if the html could not be loaded
        @$doc->loadHTML(mb_convert_encoding($content, 'HTML-ENTITIES', 'UTF-8'));
        # Get the image elements on the html
        $image_tags = $doc->getElementsByTagName('img');

 

        # If no images embedded then return the unmodified ticket
        if(empty($image_tags))
            return $result;
        
        foreach ($image_tags as $key=>$tag){
            # Get the image source
            $image_source = $tag->getAttribute('src');
            # Decode the image from base 64
            $decoded_image=base64_decode(preg_replace('#^data:image/\w+;base64,#i', '',$image_source));

            # Upload the decoded image
            $uploaded_file=UploadHelper::upload_base64_image($uploader,$decoded_image);
            $result['file_uploads'][]=$uploaded_file;
   
            # Change the content embedded image source
            $tag->setAttribute('src',$uploaded_file->url);//route('storage.view',$attachment->Dbid));
        }

        # Save the changes of the content
        $doc->saveHTML();
        $body=$doc->getElementsByTagName('html');
        if($body && 0<$body->length ) {
            $body = $body->item(0);
        } 

        $result['content']=preg_replace(['<<body>>','<</body>>'],['',''],$doc->savehtml($body));
        
        return $result;
    }
}
