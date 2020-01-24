<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_targets', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticket_id')->unsigned();
            $table->integer('target')->comment('Based on the ticket state');
            $table->timestamp('completed_date')->nullable();
            $table->timestamp('due_date')->nullable()->comment('Will be computed by the system based on the target');
            $table->integer('assigned_to')->nullable()->unsigned();
            $table->double('metrics')->comment('hourly based');
            $table->timestamps();


            $table->foreign('assigned_to')
                ->references('id')->on('users')
                ->onDelete('cascade');

            $table->foreign('ticket_id')
                ->references('id')->on('tickets')
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
        Schema::dropIfExists('ticket_targets');
    }
}
