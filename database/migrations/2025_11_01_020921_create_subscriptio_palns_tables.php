<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop table if exists (safe recreation)
        Schema::dropIfExists('subscription_plans');

        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('currency')->default('NGN');
            $table->integer('trial_days')->default(30);
            $table->enum('billing_cycle', ['monthly', 'yearly']);
            $table->boolean('is_active')->default(true);
            $table->json('features'); // ← REMOVE DEFAULT
            $table->integer('max_staff')->default(5);
            $table->integer('max_patients')->default(100);
            $table->integer('max_departments')->default(1);
            $table->timestamps();

            // Add index for faster queries
            $table->index('is_active');
            $table->index('billing_cycle');
        });

        // Insert default plans after table creation
        \DB::table('subscription_plans')->insert([
            [
                'name' => 'Free Trial',
                'description' => '30-day free trial to experience our system',
                'price' => 0.00,
                'trial_days' => 30,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'features' => json_encode([
                    'emr' => true,
                    'lab' => false,
                    'pharmacy' => false,
                    'billing' => true,
                    'appointments' => true,
                    'reports' => false,
                    'ai' => false,
                    'mobile_app' => false,
                    'sms' => false,
                    'api' => false,
                ]),
                'max_staff' => 5,
                'max_patients' => 100,
                'max_departments' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Clinic Basic',
                'description' => 'Essential features for small clinics',
                'price' => 8000.00,
                'trial_days' => 30,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'features' => json_encode([
                    'emr' => true,
                    'lab' => true,
                    'pharmacy' => true,
                    'billing' => true,
                    'appointments' => true,
                    'reports' => true,
                    'ai' => false,
                    'mobile_app' => false,
                    'sms' => true,
                    'api' => false,
                ]),
                'max_staff' => 10,
                'max_patients' => 500,
                'max_departments' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hospital Standard',
                'description' => 'Full hospital management system',
                'price' => 25000.00,
                'trial_days' => 30,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'features' => json_encode([
                    'emr' => true,
                    'lab' => true,
                    'pharmacy' => true,
                    'billing' => true,
                    'appointments' => true,
                    'reports' => true,
                    'ai' => false,
                    'mobile_app' => true,
                    'sms' => true,
                    'api' => true,
                ]),
                'max_staff' => 20,
                'max_patients' => 1000,
                'max_departments' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Enterprise Hospital',
                'description' => 'AI-powered hospital management',
                'price' => 60000.00,
                'trial_days' => 30,
                'billing_cycle' => 'monthly',
                'is_active' => true,
                'features' => json_encode([
                    'emr' => true,
                    'lab' => true,
                    'pharmacy' => true,
                    'billing' => true,
                    'appointments' => true,
                    'reports' => true,
                    'ai' => true,
                    'mobile_app' => true,
                    'sms' => true,
                    'api' => true,
                ]),
                'max_staff' => 50,
                'max_patients' => 5000,
                'max_departments' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};