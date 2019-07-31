<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FileUpload;

class FileUploadController extends Controller
{
    /**
     * Get the file from the storage
     * @param $id The database id of the file
     */
    public function get_file($id)
    {
        $file=FileUpload::find($id);
        abort_if($file==null,404);

        $exists = \Storage::disk('sci-ftp')->exists($file->file_path);

        if($exists==true)
        {
            # Get the storage disk
            $storage=\Storage::disk('sci-ftp');
            $mime_type=$storage->mimeType($file->file_path);
            $content=$storage->get($file->file_path);

            return \Response::make($content, 200, [
                'Content-Type'=> $mime_type,
                'Content-Disposition' => 'inline; filename="'.$file->file_name.'"'
            ]);
        }
        else {
            abort(404);
        }
    }
}
