<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('control_number')->unique();
            $table->text('token');
            
            $table->string('sender_name');
            $table->string('sender_email');
            $table->json('sender_carbon_copies')->nullable();
            $table->string('sender_internet_protocol_address');
            $table->string('sender_factory')->nullable();
            $table->string('sender_phone')->nullable();

            $table->string('title');
            $table->longText('content');
            $table->integer('urgency')->comment('1=low,2=normal,3=high');

            $table->integer('state')->default(1)->comment('1=Pending, 2=catered,3=PROCESSING 4=solved 5=closed 6=hold 7=cancelled');

            $table->integer('catered_by')->nullable();
            $table->timestamp('catered_at')->nullable();
            
            $table->timestamp('processing_at')->nullable();
            
            $table->text('category')->nullable();
            
            $table->text('solution')->nullable();
            $table->timestamp('solved_at')->nullable();

            $table->timestamp('closed_at')->nullable();

            $table->boolean('escalated')->default(false);

            $table->timestamp('cancelled_at')->nullable();
            $table->string('cancelled_by')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->boolean('approval')->default(false);
            $table->timestamp('approved_at')->nullable();
            $table->string('approver_name')->nullable();
            $table->string('approver_email')->nullable();
            
            $table->timestamp('hold_at')->nullable();
            $table->timestamp('un_hold_at')->nullable();

            $table->integer('user_rating')->nullable();
            $table->text('user_feedback')->nullable();

            $table->integer('ht_catered',1)->nullable()->comment('1=PASS, 0=FAIL');
            $table->integer('ht_processed',1)->nullable()->comment('1=PASS, 0=FAIL');
            $table->integer('ht_solved',1)->nullable()->comment('1=PASS, 0=FAIL');
            $table->integer('ht_closed',1)->nullable()->comment('1=PASS, 0=FAIL');
            
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
        Schema::dropIfExists('tickets');
    }
}
