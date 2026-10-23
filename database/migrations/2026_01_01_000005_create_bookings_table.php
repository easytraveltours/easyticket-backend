<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 50)->unique();
            $table->foreignId('ticket_id')->constrained('tickets')->cascadeOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();
            $table->integer('seats_count')->default(1);
            $table->string('passenger_title', 10)->default('Mr');
            $table->string('passenger_first_name', 100)->nullable();
            $table->string('passenger_last_name', 100)->nullable();
            $table->string('passenger_passport', 50)->nullable();
            $table->string('passenger_nationality', 100)->nullable();
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_amount', 12, 2);
            $table->string('currency', 10)->default('PKR');
            $table->string('status', 40)->default('RESERVED');
            $table->dateTime('expires_at');
            $table->string('payment_method', 50)->nullable();
            $table->string('payment_tx_id', 100)->nullable();
            $table->decimal('payment_amount', 12, 2)->nullable();
            $table->text('payment_proof_url')->nullable();
            $table->text('payment_proof_notes')->nullable();
            $table->dateTime('payment_submitted_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->unsignedBigInteger('verified_by_admin_id')->nullable();
            $table->text('admin_notes')->nullable();
            $table->dateTime('activated_at')->nullable();
            $table->string('pnr_issued', 50)->nullable();
            $table->string('e_ticket_number', 50)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
