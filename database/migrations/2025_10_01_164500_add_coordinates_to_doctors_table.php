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
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('state');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('hospital_name')->nullable()->after('qualification');
            $table->string('website')->nullable()->after('email');
            $table->text('services')->nullable()->after('bio');
            $table->string('languages')->nullable()->after('services');
            $table->boolean('accepts_insurance')->default(true)->after('consultation_fee');
            $table->json('insurance_accepted')->nullable()->after('accepts_insurance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude', 
                'hospital_name',
                'website',
                'services',
                'languages',
                'accepts_insurance',
                'insurance_accepted'
            ]);
        });
    }
};
