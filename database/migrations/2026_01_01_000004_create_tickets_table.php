<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_code', 80)->unique();
            $table->string('pnr', 20);
            $table->string('airline_name', 150);
            $table->string('flight_number', 50);
            $table->string('dep_airport_code', 5);
            $table->string('dep_airport_name', 150);
            $table->string('dep_city', 100);
            $table->string('dep_country', 100)->default('');
            $table->string('dep_date', 30);
            $table->string('dep_time', 20);
            $table->string('arr_airport_code', 5);
            $table->string('arr_airport_name', 150);
            $table->string('arr_city', 100);
            $table->string('arr_country', 100)->default('');
            $table->string('arr_date', 30);
            $table->string('arr_time', 20);
            $table->string('duration', 50)->default('3h 15m');
            $table->integer('stops')->default(0);
            $table->string('cabin_class', 50)->default('Economy');
            $table->string('baggage', 50)->default('30 KG');
            $table->boolean('is_refundable')->default(false);
            $table->text('ticket_rules')->nullable();
            $table->text('refund_rules')->nullable();
            $table->text('change_rules')->nullable();
            $table->decimal('public_price', 12, 2);
            $table->decimal('b2b_price', 12, 2);
            $table->string('currency', 10)->default('PKR');
            $table->integer('total_inventory')->default(10);
            $table->integer('available_inventory')->default(10);
            $table->integer('reserved_inventory')->default(0);
            $table->integer('active_inventory')->default(0);
            $table->string('status', 30)->default('AVAILABLE');
            $table->integer('low_stock_threshold')->default(3);
            $table->string('expiry_date', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
