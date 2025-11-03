<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('medical_record_number')->unique();

            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone');
            $table->string('email');
            $table->date('date_of_birth');
            $table->string('gender');
            $table->text('address')->nullable();
            $table->string('blood_type')->nullable();
            $table->text('allergies')->nullable();
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_phone');
            $table->string('insurance_provider')->nullable();
            $table->string('insurance_number')->nullable();

            $table->timestamps();
        });

        // ✅ Add indexes with prefix lengths using raw SQL
        DB::statement('ALTER TABLE `patients` ADD INDEX `patients_hospital_mrn_index`(`hospital_id`, `medical_record_number`)');
        DB::statement('ALTER TABLE `patients` ADD INDEX `patients_hospital_name_index`(`hospital_id`, `first_name`(50), `last_name`(50))');
        DB::statement('ALTER TABLE `patients` ADD INDEX `patients_hospital_phone_index`(`hospital_id`, `phone`(20))');
        DB::statement('ALTER TABLE `patients` ADD INDEX `patients_hospital_email_index`(`hospital_id`, `email`(100))');
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};