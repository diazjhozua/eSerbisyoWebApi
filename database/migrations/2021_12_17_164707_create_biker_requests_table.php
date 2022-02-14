<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBikerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('biker_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('phone_no')->unique();
            $table->string('bike_type');
            $table->string('bike_size');
            $table->string('bike_color')->nullable();
            $table->enum('status', ['Pending', 'Denied', 'Approved'])->default('Pending');
            $table->string('admin_message')->nullable();
            $table->string('credential_name');
            $table->string('credential_file_path');
            $table->string('reason');
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
        Schema::dropIfExists('biker_requests');
    }
}
