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
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('agency_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('agency_id')->references('id')->on('agencies')
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('agency_id')->unsigned();
            $table->integer('template_id')->unsigned();
            $table->integer('project_id')->unsigned();
            $table->integer('product_manager_id')->nullable();
            $table->string('folder_id')->nullable();
            $table->string('item_number')->default('n.a');
            $table->string('design_type')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('deadline');
            $table->string('country')->default('EU');
            $table->boolean('private')->default(false);
            $table->boolean('archivied')->default(false);
            $table->boolean('billed')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();

            $table->foreign('agency_id')->references('id')->on('agencies')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('template_id')->references('id')->on('templates')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('project_id')->references('id')->on('projects')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
        Schema::dropIfExists('tasks');
    }
}
