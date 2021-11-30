<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('contact_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            $table->string('custom_type')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->longText('reason');
            $table->longText('action');
            $table->enum('status', ['Pending', 'Denied', 'Approved', 'Resolved'])->default('Pending');
            $table->string('admin_message')->nullable();
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
        Schema::dropIfExists('complaints');
    }
}
