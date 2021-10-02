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
            $table->foreignId('ordered_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->float('total_price', 4,2);
            $table->enum('pick_up_type', ['Pickup', 'Delivery'])->default('Pickup');
            $table->float('delivery_fee', 4,2);
            $table->date('pickup_date');
            $table->enum('application_status', ['Pending', 'Cancelled', 'Approved', 'Denied'])->default('Pending');
            $table->enum('order_status', ['Waiting', 'Received', 'DNR'])->default('Waiting');
            $table->boolean('is_received');
            $table->string('location_address');
            $table->float('location_long', 10, 10);
            $table->float('location_lat', 10, 10);
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
        Schema::dropIfExists('orders');
    }
}
