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
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('specialty');
            $table->string('qualification');
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('city');
            $table->string('state')->nullable();
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('years_experience')->default(0);
            $table->json('available_days')->nullable();
            $table->time('available_from')->nullable();
            $table->time('available_to')->nullable();
            $table->decimal('consultation_fee', 8, 2)->nullable();
            $table->string('image_url')->nullable();
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctors');
    }
};
