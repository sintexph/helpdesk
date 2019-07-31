<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->json('applications');
            $table->json('fields');
            $table->longText('format')->nullable();

            $table->string('field_sender_id_number')->nullable();
            $table->string('field_sender_name')->nullable();
            $table->string('field_sender_email')->nullable();
            $table->string('field_sender_factory')->nullable();
            $table->string('field_sender_phone')->nullable();

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
        Schema::dropIfExists('custom_requests');
    }
}
