<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketConversationAttachmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_conversation_attachments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_conversation_id')->unsigned();
            $table->integer('file_upload_id')->unsigned();
            $table->timestamps();

            $table->foreign('ticket_conversation_id')
                ->references('id')->on('ticket_conversations')
                ->onDelete('cascade');
                
            $table->foreign('file_upload_id')
                ->references('id')->on('file_uploads')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_conversation_attachments');
    }
}
