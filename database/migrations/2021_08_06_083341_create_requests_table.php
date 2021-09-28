<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            $table->date('birthday')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('contact_no')->nullable();
            $table->string('contact_person')->nullable();
            $table->string('contact_person_no')->nullable();
            $table->string('contact_person_relation')->nullable();
            $table->string('citizenship')->nullable();
            $table->string('purpose')->nullable();
            $table->date('date_requested')->nullable();
            $table->date('date_released')->nullable();
            $table->date('date_expiry')->nullable();
            $table->string('precint_no')->nullable();
            $table->enum('civil_status', ['Single', 'Married', 'Divorced', 'Widowed'])->default('Single');
            $table->string('received_by')->nullable();
            $table->string('signature_picture');
            $table->string('file_path');
            $table->enum('status', ['Pending', 'Denied', 'Approved'])->default('Pending');
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
        Schema::dropIfExists('requests');
    }
}
