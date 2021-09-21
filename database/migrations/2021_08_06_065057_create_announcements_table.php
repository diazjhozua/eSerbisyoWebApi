<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            $table->string('custom_type')->nullable();
            $table->string('title');
            $table->longText('description');
            $table->timestamps();

            // $table->id();
            // $table->foreignId('announcement_type_id')->nullable()->constrained('announcement_types')->onDelete('set null');
            // $table->string('title');
            // $table->longText('description');
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('announcements');
    }
}
