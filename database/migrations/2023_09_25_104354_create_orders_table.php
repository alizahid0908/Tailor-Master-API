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
            $table->unsignedInteger('customer_id')->nullable()->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable()->onDelete('cascade');
            $table->bigInteger('price')->required(); 
            $table->enum('status', ['pending', 'delivered'])->default('pending');
            $table->timestamps();

            // $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
