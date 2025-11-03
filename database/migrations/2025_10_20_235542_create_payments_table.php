<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('payment_type'); // 'appointment', 'billing', 'subscription'
            $table->string('payment_gateway'); // 'paystack', 'flutterwave'
            $table->string('reference')->unique(); // your system's reference
            $table->string('gateway_reference')->nullable(); // gateway's own ref (for verification)
            $table->bigInteger('amount'); // in **kobo** (e.g., ₦1000 = 100000)
            $table->string('currency')->default('NGN');
            $table->string('status'); // 'pending', 'successful', 'failed', 'cancelled'
            $table->json('metadata')->nullable(); // must include 'hospital_id'
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};