<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hospital_payment_settings', function (Blueprint $table) {
            // Increase to TEXT to safely store encrypted data (up to 65KB)
            $table->text('secret_key')->change();
            $table->text('webhook_secret')->nullable()->change();
            // public_key can stay as VARCHAR (it's not encrypted)
        });
    }

    public function down(): void
    {
        Schema::table('hospital_payment_settings', function (Blueprint $table) {
            $table->string('secret_key', 191)->change();
            $table->string('webhook_secret', 191)->nullable()->change();
        });
    }
};