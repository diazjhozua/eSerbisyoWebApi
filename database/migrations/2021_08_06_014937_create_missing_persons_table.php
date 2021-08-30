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
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->float('height', 5,2);
            $table->float('weight', 5,2);
            $table->integer('age');
            $table->string('eyes')->nullable();
            $table->string('hair')->nullable();
            $table->string('unique_sign');
            $table->string('important_information');
            $table->string('last_seen');
            $table->string('contact_information');
            $table->string('picture_name')->nullable();
            $table->string('file_path')->nullable();
            $table->boolean('is_found')->default(0);
            $table->boolean('is_resolved')->default(0);
            $table->boolean('is_approved')->default(0);

            $table->timestamps();

            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');
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
