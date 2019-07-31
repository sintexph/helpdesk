<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name');
            $table->string('file_type');
            $table->text('file_path');
            $table->text('file_mime');
            $table->text('file_size');
            $table->string('uploaded_by');
            $table->boolean('has_preview')->default(false)->comment('Preview only for PDF files');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('file_uploads');
    }
}
