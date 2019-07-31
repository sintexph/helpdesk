<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('content');
            $table->string('created_by');
            $table->integer('ticket_id')->unsigned();
            $table->timestamps();


            $table->foreign('ticket_id')
                ->references('id')->on('tickets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
                
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_notes');
    }
}
