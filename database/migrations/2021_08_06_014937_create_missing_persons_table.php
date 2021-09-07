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
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->float('height', 5,2);
            $table->float('weight', 5,2);
            $table->integer('age')->nullable();
            $table->string('eyes')->nullable();
            $table->string('hair')->nullable();
            $table->string('unique_sign');
            $table->string('important_information');
            $table->string('last_seen');
            $table->string('contact_information');
            $table->string('picture_name');
            $table->string('file_path');
            $table->integer('status');
            $table->integer('report_type');
            $table->timestamps();

            // application status
            // 1 - for approval
            // 2 - approved
            // 3 - denied
            // 4 - resolved

            // missing report type
            // 1 - Missing
            // 2 - Found
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
