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
            $table->string('picture')->nullable();
            $table->unsignedBigInteger('purok_id')->nullable();
            $table->string('address');

            // User Status
            $table->boolean('is_verified')->nullable();
            $table->string('status')->nullable();
            $table->string('credentials')->nullable();

            // User Location
            $table->float('long', 8, 2)->nullable();
            $table->float('lat', 8, 2)->nullable();

            // User Previledge
            $table->unsignedBigInteger('user_role_id');

            $table->rememberToken();
            $table->timestamps();

            $table->foreign('purok_id')->references('id')
                ->on('puroks')->onDelete('set null');

            $table->foreign('user_role_id')->references('id')
                ->on('user_roles')->onDelete('cascade');
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
