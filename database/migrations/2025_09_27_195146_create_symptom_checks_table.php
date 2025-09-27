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
        Schema::create('symptom_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->json('symptoms'); // Array of symptoms reported
            $table->text('description')->nullable(); // User's description
            $table->json('ai_analysis')->nullable(); // AI's analysis and suggestions
            $table->enum('urgency_level', ['low', 'medium', 'high', 'emergency']);
            $table->text('recommendations')->nullable();
            $table->boolean('doctor_recommended')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('symptom_checks');
    }
};
