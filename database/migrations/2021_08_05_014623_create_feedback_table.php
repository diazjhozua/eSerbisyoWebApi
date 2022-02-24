<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('type_id')->nullable()->constrained('types')->onDelete('set null');
            $table->string('custom_type')->nullable();
            $table->integer('rating');
            // $table->enum('polarity', ['Positive', 'Neutral', 'Negative']);
            $table->longText('message');
            $table->string('admin_respond')->nullable();
            $table->enum('status', ['Pending', 'Ignored', 'Noted'])->default('Pending');
            $table->boolean('is_anonymous');
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
        Schema::dropIfExists('feedbacks');

    }
}
