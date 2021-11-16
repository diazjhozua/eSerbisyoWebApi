<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMissingPersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('missing_persons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->float('height', 5,2);
            $table->enum('height_unit', ['feet(ft)', 'centimeter(cm)']);
            $table->float('weight', 5,2);
            $table->enum('weight_unit', ['kilogram(kg)', 'pound(lbs)']);
            $table->integer('age')->nullable();
            $table->string('eyes')->nullable();
            $table->string('hair')->nullable();
            $table->string('unique_sign');
            $table->string('important_information');
            $table->string('last_seen');
            $table->string('contact_information');
            $table->string('picture_name');
            $table->string('file_path');
            $table->enum('status', ['Pending', 'Denied', 'Approved', 'Resolved'])->default('Pending');
            $table->string('admin_message')->nullable();
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
        Schema::dropIfExists('missing_persons');
    }
}
