<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ordinances', function (Blueprint $table) {
            $table->id();
            $table->string('ordinance_no')->unique();
            $table->longText('title');
            $table->date('date_approved');
            $table->unsignedBigInteger('ordinance_category_id')->nullable();
            $table->string('pdf_name');
            $table->string('file_path');
            $table->timestamps();

            $table->foreign('ordinance_category_id')->references('id')
                ->on('ordinance_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ordinances');
    }
}
