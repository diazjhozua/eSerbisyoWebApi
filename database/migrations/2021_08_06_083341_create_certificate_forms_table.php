<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateFormsTable extends Migration
{
    /**
     * Run the migrations.
    *e
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('certificate_id')->constrained('certificates')->onDelete('cascade');

            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->float('height', 5,2)->nullable();
            $table->float('weight', 5,2)->nullable();
            $table->string('profession')->nullable();
            $table->float('price_filled', 100,2)->nullable();

            $table->integer('tin_no')->nullable();
            $table->integer('icr_no')->nullable();
            $table->float('basic_tax', 5,2)->default(0);
            $table->float('additional_tax', 5,2)->default(0);
            $table->float('gross_receipt_preceding', 5,2)->default(0);
            $table->float('gross_receipt_profession', 5,2)->default(0);
            $table->float('real_property', 5,2)->default(0);
            $table->float('interest', 5,2)->default(0);
            $table->enum('cedula_type', ['Individual', 'Corporation'])->nullable();
            $table->enum('sex', ['Male', 'Female'])->default('Male');

            $table->string('address');
            $table->string('business_name')->nullable();
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
            // $table->string('signature_picture');
            // $table->string('file_path');
            $table->enum('status', ['Pending', 'Cancelled', 'Approved', 'Denied'])->default('Pending');
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
        Schema::dropIfExists('certificate_forms');
    }
}
