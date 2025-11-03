<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pharmacy_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('report_type'); // e.g., "daily", "weekly", "monthly", "inventory"
            $table->date('start_date');
            $table->date('end_date');
            $table->json('data'); // Store report data as JSON
            $table->string('generated_by');
            $table->string('report_path'); // Path to PDF file
            $table->boolean('is_shared_with_admin')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pharmacy_reports');
    }
};