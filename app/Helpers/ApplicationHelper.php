<?php

namespace App\Helpers;
use App\Upload;
use File;
use App\TicketConversation;
use App\User;
use App\Helpers\ContentHelper;
use App\TicketConversationAttachment;
use App\CustomRequest;

class ApplicationHelper 
{
    const KEY_START_TAG='|';
    const KEY_END_TAG='|';
    


    /**
     * Remove the trailing underscore in object/array keys
     * @param $array The object/array
     */
    public static function generalize_keys($array)
    {
        $encode=json_encode($array);
        $encode=str_replace("\"_","\"",$encode);
        return json_decode($encode);
    }



    /**
     * GENERATE TICKET FIELDS FOR CUSTOM REQUEST
     */
    public static function ticket_fields(CustomRequest $application,User $sender)
    {
        $ticket=[
            'sender_name'=>'NONE',
            'sender_email'=>'NONE',
            'sender_phone'=>'NONE',
            'sender_factory'=>'NONE',
            'sender_id_number'=>'NONE',
        ];
        $ticket=(Object)$ticket;
 
        

        foreach ($application->fields as $field) {
 
            switch($field['id'])
            {
                case $application->field_sender_name:
                    $ticket->sender_name=$field['data']['value'];
                break;
                case $application->field_sender_email:
                    $ticket->sender_email=$field['data']['value'];
                break;
                case $application->field_sender_phone:
                    $ticket->sender_phone=$field['data']['value'];
                break;
                case $application->field_sender_factory:
                    $ticket->sender_factory=$field['data']['value'];
                break;
                case $application->field_sender_id_number:
                    $ticket->sender_id_number=$field['data']['value'];
                break;
            }
        }

        # Prioritize user fill in if there are no custom fields put in
        if(empty($application->field_sender_name))
            $ticket->sender_name=$sender->name;

        if(empty($application->field_sender_email))
            $ticket->sender_email=$sender->email;

        if(empty($application->field_sender_phone))
            $ticket->sender_phone=$sender->contact;

        if(empty($application->field_sender_factory))
            $ticket->sender_factory=$sender->factory;

        if(empty($application->field_sender_id_number))
            $ticket->sender_id_number=$sender->id_number;

        return $ticket;
    }

    /**
     * Generate format with values in fields
     */
    public static function generate_field_format(CustomRequest $application,User $sender,$application_name=null)
    {
        $format=$application->format;

        $format=static::generate_value($application['fields'],$format);

        
        // Set the date to the current server date
        $format=\str_replace(static::KEY_START_TAG.'created_at'.static::KEY_END_TAG,\Carbon\Carbon::now()->format('M d, Y'),$format);


        $ticket_info=static::ticket_fields($application,$sender);
   
        
        $format=\str_replace(static::KEY_START_TAG.'sender_id_number'.static::KEY_END_TAG,$ticket_info->sender_id_number,$format);
        $format=\str_replace(static::KEY_START_TAG.'sender_name'.static::KEY_END_TAG,$ticket_info->sender_name,$format);
        $format=\str_replace(static::KEY_START_TAG.'sender_email'.static::KEY_END_TAG,$ticket_info->sender_email,$format);
        $format=\str_replace(static::KEY_START_TAG.'sender_contact'.static::KEY_END_TAG,$ticket_info->sender_phone,$format);
        $format=\str_replace(static::KEY_START_TAG.'sender_factory'.static::KEY_END_TAG,$ticket_info->sender_factory,$format);


        if($application_name!=null)
            $format=\str_replace(static::KEY_START_TAG.'--application_name--'.static::KEY_END_TAG,$application_name,$format);
        
  

        return $format;
    }

    // Still have a problem
    public static function generate_value($fields,$format)
    { 
        foreach ($fields as $field)
        { 
            switch(\strtolower($field['type']))
            {
                case 'set';
                
                    $format=static::generate_loop_set_format($field,$format); 

                break;

                default:

                    $format=\str_replace(static::KEY_START_TAG.$field['id'].static::KEY_END_TAG,nl2br($field['data']['value']),$format);

                break;
            }
            
        } 
        
        return $format;
    }

    public static function generate_loop_set_format($field,$format)
    {
        # Generate looping pattern
        $start_tag=static::KEY_START_TAG."loop_".$field['id'].static::KEY_END_TAG;
        $end_tag=static::KEY_START_TAG."end_loop_".$field['id'].static::KEY_END_TAG;
 
        $template=static::get_string_between($format,$start_tag,$end_tag);
        $toTransform='';
               
        # Render Set items
        foreach ($field['data']['items'] as $key=>$item) { 
            
            
            $temp_template=$template;
            
            # Replace if there will be numbering that is set
            $temp_template=str_replace(static::KEY_START_TAG."{".$field['id']."}{index_key}".static::KEY_END_TAG,($key+1),$temp_template);

            # Replace the dynamic data template with the actual value
            foreach ($item['fields'] as $set_field) { 
                
                $set_data=$set_field['data']; 
                $temp_template=str_replace(static::KEY_START_TAG."{".$field['id']."}{".$set_field['id']."}".static::KEY_END_TAG,nl2br($set_data['value']),$temp_template);
            }
            
            $toTransform.=$temp_template;
        }
        
        return static::delete_all_between($format,$start_tag,$end_tag,$toTransform);
    }
    private static function get_string_between($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    # Replace the dynamic template with the actual data
    private static function delete_all_between($source,$start, $end, $new) {

        $str=preg_replace('#('.preg_quote($start).')(.*?)('.preg_quote($end).')#si', '$1'.$new.'$3', $source);
        $str=str_replace($start,"",$str);
        $str=str_replace($end,"",$str);
        return $str;
    }
}
