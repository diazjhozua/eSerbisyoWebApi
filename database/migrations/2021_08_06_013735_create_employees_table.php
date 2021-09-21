<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('term_id')->nullable()->constrained('terms')->onDelete('set null');
            $table->string('custom_term')->nullable();
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');
            $table->string('custom_position')->nullable();
            $table->longText('description');
            $table->string('picture_name');
            $table->string('file_path');
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
        Schema::dropIfExists('employees');
    }
}
