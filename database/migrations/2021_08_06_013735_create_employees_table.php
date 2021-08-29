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
            $table->foreignId('position_id')->nullable()->constrained('positions')->onDelete('set null');
            $table->longText('description')->nullable();
            $table->string('picture_name')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            // $table->unsignedBigInteger('term_id')->nullable();
            // $table->unsignedBigInteger('position_id')->nullable();

            // $table->unsignedBigInteger('term_id')->nullable();
            // $table->unsignedBigInteger('position_id')->nullable();
            // $table->foreign('term_id')->references('id')
            //     ->on('terms')->onDelete('set null');

            // $table->foreign('position_id')->references('id')
            //     ->on('positions')->onDelete('set null');
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
