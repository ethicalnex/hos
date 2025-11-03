<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade');
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade'); // doctor user_id
            $table->foreignId('nurse_id')->nullable()->constrained('users')->onDelete('set null'); // optional

            // Clinical data
            $table->text('symptoms')->nullable();
            $table->string('temperature')->nullable(); // e.g., "37.5°C"
            $table->string('blood_pressure')->nullable(); // e.g., "120/80 mmHg"
            $table->string('pulse')->nullable(); // BPM
            $table->string('respiratory_rate')->nullable(); // breaths/min
            $table->string('weight')->nullable(); // kg
            $table->string('height')->nullable(); // cm

            $table->text('diagnosis');
            $table->text('treatment_plan');
            $table->text('doctor_notes')->nullable();
            $table->text('nurse_notes')->nullable();

            $table->timestamps();
        });

        // Index for performance
        Schema::table('medical_records', function (Blueprint $table) {
            $table->index(['patient_id', 'created_at']);
            $table->index(['doctor_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};