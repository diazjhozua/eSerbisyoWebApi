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
            $table->string('ordinance_no');
            $table->longText('title');
            $table->date('date_approved');
            $table->foreignId('ordinance_category_id')->nullable()->constrained('ordinance_categories')->onDelete('set null');
            $table->string('pdf_name');
            $table->string('file_path');
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
        Schema::dropIfExists('ordinances');
    }
}
