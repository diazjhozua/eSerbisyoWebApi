<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificateRequirementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificate_requirement', function (Blueprint $table) {
            $table->unsignedBigInteger('certificate_id');
            $table->unsignedBigInteger('requirement_id');
            $table->timestamps();

            $table->foreign('certificate_id')->references('id')
                ->on('certificates')->onDelete('cascade');

            $table->foreign('requirement_id')->references('id')
                ->on('requirements')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificate_requirement');
    }
}
