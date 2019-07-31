<?php

namespace App\Observers;

use App\FileUpload;
use App\Helpers\UploadHelper;

class UploadObserver
{
    
    /**
     * Handle the upload "deleted" event.
     * Delete the files on the storage if the file upload model is deleted
     * @param  \App\FileUpload  $upload
     * @return void
     */
    public function deleted(FileUpload $upload)
    {
        UploadHelper::delete_file($upload->file_path);
    }
    

    /**
     * Handle the upload "force deleted" event.
     *
     * @param  \App\FileUpload  $upload
     * @return void
     */
    public function forceDeleted(FileUpload $upload)
    {
        //
    }
}
