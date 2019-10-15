<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {

            $table->increments('id');
            
            $table->string('name');
            $table->longText('description');
            $table->integer('project_id')->unsigned()->nullable();

            $table->integer('created_by')->unsigned();
            $table->integer('assigned_to')->unsigned();

            $table->date('start_date')->nullable();
            $table->date('due_date')->nullable();


            $table->string('label');
            
            $table->integer('state')->default(1);
            $table->integer('priority');

            $table->longText('remarks')->nullable();

            $table->integer('order')->nullable();
            
            $table->timestamps();

            $table->foreign('project_id')
                ->references('id')->on('projects')
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
        Schema::dropIfExists('tasks');
    }
}
