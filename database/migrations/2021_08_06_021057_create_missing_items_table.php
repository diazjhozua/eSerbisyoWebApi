<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contact_user_id')->constrained('users')->onDelete('cascade');
            $table->string('item');
            $table->string('last_seen');
            $table->string('description');
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('picture_name');
            $table->string('file_path');
            $table->enum('status', ['Pending', 'Denied', 'Approved', 'Resolved'])->default('Pending');
            $table->string('admin_message')->nullable();
            $table->enum('report_type', ['Missing', 'Found']);
            $table->string('credential_name')->nullable();
            $table->string('credential_file_path')->nullable();
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
        Schema::dropIfExists('missing_items');
    }
}
