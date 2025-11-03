<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('super_admin_payment_settings', function (Blueprint $table) {
            $table->id();
            $table->enum('payment_gateway', ['paystack', 'flutterwave']);
            $table->string('public_key');
            $table->string('secret_key');
            $table->text('webhook_secret')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['payment_gateway']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('super_admin_payment_settings');
    }
};