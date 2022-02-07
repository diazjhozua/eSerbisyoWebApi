<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{

    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ordered_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('delivered_by')->nullable()->constrained('users')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('location_address')->nullable();
            $table->float('total_price', 100,2)->default(0);
            $table->float('delivery_fee', 200,2)->default(0);
            $table->date('pickup_date')->nullable();
            $table->date('received_at')->nullable();
            $table->enum('application_status', ['Pending', 'Cancelled', 'Approved', 'Denied'])->default('Pending');
            $table->enum('delivery_payment_status', ['Pending', 'Received'])->nullable();
            $table->enum('pick_up_type', ['Walkin', 'Pickup', 'Delivery'])->default('Pickup');
            $table->enum('order_status', ['Pending', 'Waiting', 'Accepted', 'On-Going', 'Received', 'DNR'])->default('Waiting')->nullable();
            $table->string('admin_message')->nullable();
            $table->float('user_long', 100, 10)->nullable();
            $table->float('user_lat', 100, 10)->nullable();
            $table->float('rider_long', 100, 10)->nullable();
            $table->float('rider_lat', 100, 10)->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
