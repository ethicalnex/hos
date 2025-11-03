<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing table if it exists
        if (Schema::hasTable('hospital_subscriptions')) {
            Schema::dropIfExists('hospital_subscriptions');
        }

        Schema::create('hospital_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->string('payment_gateway')->nullable();
            $table->string('payment_reference')->nullable();
            $table->decimal('amount_paid', 10, 2);
            $table->date('starts_at');
            $table->date('ends_at');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospital_subscriptions');
    }
};