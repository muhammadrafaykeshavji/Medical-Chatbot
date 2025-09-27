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
        Schema::create('health_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('metric_type', ['blood_pressure', 'blood_sugar', 'weight', 'heart_rate', 'temperature']);
            $table->decimal('systolic', 5, 2)->nullable(); // For blood pressure
            $table->decimal('diastolic', 5, 2)->nullable(); // For blood pressure
            $table->decimal('value', 8, 2); // General value for other metrics
            $table->string('unit', 20); // mg/dL, kg, lbs, bpm, °C, °F, etc.
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_metrics');
    }
};
