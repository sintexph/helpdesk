<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('username')->unique();
            $table->string('id_number')->unique();
            $table->string('factory');
            $table->string('position');
            $table->string('contact')->default('000000');

            $table->integer('role')->unsigned()->default(1)->comment('1=sender,2=support,3=moderator,4=administrator'); 

            $table->boolean('active')->default(true);

            $table->integer('shift_start')->unsigned()->nullable();
            $table->integer('shift_end')->unsigned()->nullable();
            
            $table->double('break_time')->unsigned()->nullable()->comment('hourly based');

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
