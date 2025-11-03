<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_features', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default features
        \DB::table('subscription_features')->insert([
            ['name' => 'EMR', 'slug' => 'emr', 'is_active' => true],
            ['name' => 'Lab Management', 'slug' => 'lab', 'is_active' => true],
            ['name' => 'Pharmacy', 'slug' => 'pharmacy', 'is_active' => true],
            ['name' => 'Billing & Invoicing', 'slug' => 'billing', 'is_active' => true],
            ['name' => 'Appointment Booking', 'slug' => 'appointments', 'is_active' => true],
            ['name' => 'Reports & Analytics', 'slug' => 'reports', 'is_active' => true],
            ['name' => 'AI Diagnostics', 'slug' => 'ai', 'is_active' => true],
            ['name' => 'Mobile App Access', 'slug' => 'mobile_app', 'is_active' => true],
            ['name' => 'SMS Reminders', 'slug' => 'sms', 'is_active' => true],
            ['name' => 'API Integration', 'slug' => 'api', 'is_active' => true],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_features');
    }
};