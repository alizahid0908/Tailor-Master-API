<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('order_id')->nullable()->onDelete('cascade');
            $table->unsignedInteger('size_id')->nullable()->onDelete('cascade');
            $table->integer('quantity')->required();
            $table->timestamps();

            // $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            // $table->foreign('size_id')->references('id')->on('sizes')->onDelete('cascade');
        });
    }

    public function down()
    {

        Schema::dropIfExists('order_items');
    }
}
