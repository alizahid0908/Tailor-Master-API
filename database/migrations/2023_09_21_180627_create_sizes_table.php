<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size_name'); 
            $table->float('collar_size', 8, 2)->nullable();
            $table->float('chest_size', 8, 2)->nullable();
            $table->float('sleeve_length', 8, 2)->nullable();
            $table->float('cuff_size', 8, 2)->nullable();
            $table->float('shoulder_size', 8, 2)->nullable();
            $table->float('waist_size', 8, 2)->nullable();
            $table->float('shirt_length', 8, 2)->nullable();
            $table->float('legs_length', 8, 2)->nullable();
            $table->string('description')->nullable();
            $table->enum('category', ['shirt_pant', 'kurta', 'blazer', 'kameez_shalwar']);
            $table->unsignedInteger('customer_id')->nullable()->onDelete('cascade');
            $table->unsignedInteger('user_id')->nullable()->onDelete('cascade');    
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::dropIfExists('sizes');   
    }
};
