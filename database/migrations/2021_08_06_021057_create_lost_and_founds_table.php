<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLostAndFoundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('item');
            $table->string('last_seen');
            $table->string('description');
            $table->string('contact_information');
            $table->string('picture_name');
            $table->string('file_path');
            $table->enum('status', ['Pending', 'Denied', 'Approved', 'Resolved'])->default('Pending');
            $table->enum('report_type', ['Missing', 'Found']);
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
        Schema::dropIfExists('lost_and_founds');
    }
}
