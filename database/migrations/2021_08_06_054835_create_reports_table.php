<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            // $table->foreignId('report_type_id')->nullable()->constrained('report_types')->onDelete('set null');
            $table->string('custom_type')->nullable();
            $table->string('location_address');
            $table->string('landmark');
            $table->longText('description');
            $table->boolean('is_anonymous');
            $table->boolean('is_urgent');
            $table->string('admin_message')->nullable();
            $table->integer('status');
            $table->string('picture_name')->nullable();
            $table->string('file_path')->nullable();
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
        Schema::dropIfExists('reports');
    }
}
