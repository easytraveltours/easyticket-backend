<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->string('role', 20)->default('agent'); // admin | agent
            $table->string('status', 30)->default('pending'); // pending | active | suspended | rejected
            $table->string('phone', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('company_name', 200)->nullable();
            $table->string('travel_agency_name', 200)->nullable();
            $table->string('registration_number', 100)->nullable();
            $table->text('business_address')->nullable();
            $table->string('country', 100)->default('Pakistan');
            $table->string('city', 100)->default('Islamabad');
            $table->string('website', 200)->nullable();
            $table->string('expected_monthly_bookings', 50)->nullable();
            $table->string('years_in_business', 20)->nullable();
            $table->text('documents_url')->nullable();
            $table->foreignId('group_id')->nullable()->constrained('agent_groups')->nullOnDelete();
            $table->decimal('wallet_balance', 12, 2)->default(0.00);
            $table->decimal('credit_limit', 12, 2)->default(0.00);
            $table->dateTime('last_login')->nullable();
            $table->dateTime('last_activity')->nullable();
            $table->string('current_page', 255)->nullable();
            $table->boolean('is_online')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
