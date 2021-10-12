<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            $table->string('custom_type')->nullable();
            $table->string('name');
            $table->longText('description');
            $table->float('cost', 255, 2);
            $table->date('project_start');
            $table->date('project_end');
            $table->string('location');
            $table->string('pdf_name');
            $table->string('file_path');
            // $table->boolean('is_starting');
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
        Schema::dropIfExists('projects');
    }
}
