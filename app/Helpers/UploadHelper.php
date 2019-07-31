<?php

namespace App\Helpers;
use App\FileUpload;
use File;
use App\User;
use App\Ticket;
use Sintex\Helper\FileHelper;

class UploadHelper 
{
    private const DEFAULT_DIRECTORY='uploads';
    /**
     * Generate unique file upload name
     */
    public static function generate_unique_file_name(FileUpload $file_upload,$filename,$extension=null)
    {
        $file_name='';
        if($extension==null)
            $file_name= $file_upload->id.'_'.strtolower(\Carbon\Carbon::now()->format('Ymd_H_i_s').'_'.$filename);
        else
            $file_name= $file_upload->id.'_'.strtolower(\Carbon\Carbon::now()->format('Ymd_H_i_s').'_'.$filename).'.'.$extension;
        
        return str_replace(' ','_',$file_name);
    }


    /**
     * FileUpload file to the disk storage
     * @param $file The file to be uploaded
     * @param $user The user who will upload the file
     */
    public static function upload_file($file,$uploader)
    {

        $storage=self::storage();
        $filename=$file->getClientOriginalName();


          

        # Create first a data upload on the database
        $upload= new FileUpload;
        $upload->file_name=$filename;
        $upload->file_path='temporary';
        $upload->file_type='Document';
        $upload->file_mime='temporary_mime';
        $upload->file_size=$file->getClientSize();
        $upload->uploaded_by=$uploader;
        $upload->save();

        # Formulate the the file store name
        $store_name=static::generate_unique_file_name($upload,$filename);//$upload->id.'_'.strtolower(\Carbon\Carbon::now()->format('Ymd_H_i_s').'_'.$filename);
        $store_name=str_replace(' ','_',$store_name);
        
        # Upload the file to the storage
        $uploaded_file=$storage->putFileAs(static::DEFAULT_DIRECTORY, $file,$store_name);
        $mime=$storage->mimeType($uploaded_file);

        # Change the path on the database data
        $upload->file_path=$uploaded_file;
        $upload->file_mime=$mime;
         # Set the preview flag only for pdf
        if (strpos(strtolower($mime), 'pdf') !== false) {
            $upload->has_preview=true;
        }
        $upload->save();

        
        return $upload;
    }
 


    /**
     * Upload base64 image to storage
     * @param $ticket
     */
    public static function upload_base64_image($uploader,$decoded_img)
    {
        $storage=self::storage();
        $file_upload=static::init_file_upload();

        $extension='png';
        $store_name=static::generate_unique_file_name($file_upload,'embedded_image',$extension);
        $full_path=static::DEFAULT_DIRECTORY.'/'.$store_name;
        $file=$storage->put($full_path, $decoded_img);
        
        $file_upload->file_name=$store_name;
        $file_upload->file_path=$full_path;
        $file_upload->file_type='Image';
        $file_upload->file_mime='image/png';
        $file_upload->file_size='0KB';
        $file_upload->uploaded_by=$uploader;
        $file_upload->save();

        return $file_upload;
    }

    public static function upload_decoded_file($uploader,$decoded_file,$file_name,$type,$mime,$size)
    {
        $storage=self::storage();
        $file_upload=static::init_file_upload();

        $extension='png';
        $store_name=static::generate_unique_file_name($file_upload,$file_name);
        $full_path=static::DEFAULT_DIRECTORY.'/'.$store_name;
        $file=$storage->put($full_path, $decoded_file);
        
        $file_upload->file_name=$file_name;
        $file_upload->file_path=$full_path;
        $file_upload->file_type=$type;
        $file_upload->file_mime=$mime;
        $file_upload->file_size=FileHelper::formatSizeUnits($size);
        $file_upload->uploaded_by=$uploader;
        $file_upload->save();

        return $file_upload;
    }
    public static function storage()
    {
        return \Storage::disk('sci-ftp');
    }

    /**
     * Delete the file from storage
     */
    public static function delete_file($path)
    {
        return self::storage()->delete($path);
    }



    /**
     * Initialize new instance of the FileUpload model and set dummy content
     */
    private static function init_file_upload()
    {
        # Create first a data upload on the database
        $upload= new FileUpload;
        $upload->file_name='---';
        $upload->file_path='---';
        $upload->file_type='---';
        $upload->file_mime='---';
        $upload->file_size='---';
        $upload->uploaded_by='---';
        $upload->save();

        return $upload;
    }


}
