<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            // User login credentials
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // User Information
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('barangay_id')->unique()->nullable();
            $table->unsignedBigInteger('purok_id')->nullable()->constrained('puroks')->onDelete('set null');
            $table->string('address');
            $table->string('picture_name');
            $table->string('file_path');

            // User Status
            $table->boolean('is_verified')->nullable();
            $table->enum('status', ['Enable', 'Disable'])->default('Enable');
            $table->string('admin_status_message')->nullable();

            // User Previledge
            $table->unsignedBigInteger('user_role_id')->on('user_roles')->onDelete('set null');;

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
