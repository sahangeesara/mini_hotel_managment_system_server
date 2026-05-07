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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->char('hotel_code')->unique();
            $table->string('hotel_name');
            $table->string('location');
            $table->string('city');
            $table->string('country');
            $table->string('image')->nullable();
            $table->decimal('price_per_night', 10, 2);
            $table->integer('star_rating');
            $table->json('amenities')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
