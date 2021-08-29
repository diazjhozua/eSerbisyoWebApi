<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ordered_by_user_id');
            $table->unsignedBigInteger('delivered_by_user_id')->nullable();
            $table->float('total_certificate_price', 4,2);
            $table->boolean('is_pickup');
            $table->float('delivery_fee', 4,2);
            $table->date('pickup_date');
            $table->date('delivery_date');
            $table->string('status');
            $table->boolean('is_approved');
            $table->timestamps();

            $table->foreign('ordered_by_user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->foreign('delivered_by_user_id')->references('id')
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
        Schema::dropIfExists('orders');
    }
}
