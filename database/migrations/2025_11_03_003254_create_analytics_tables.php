<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospital_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->integer('total_patients')->default(0);
            $table->integer('new_patients')->default(0);
            $table->integer('total_appointments')->default(0);
            $table->integer('completed_appointments')->default(0);
            $table->integer('cancelled_appointments')->default(0);
            $table->decimal('total_revenue', 10, 2)->default(0);
            $table->integer('total_staff')->default(0);
            $table->integer('active_staff')->default(0);
            $table->timestamps();

            $table->unique(['hospital_id', 'date']);
        });

        Schema::create('analytics_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('report_type'); // 'daily', 'weekly', 'monthly', 'yearly'
            $table->json('data');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('analytics_reports');
        Schema::dropIfExists('hospital_metrics');
    }
};