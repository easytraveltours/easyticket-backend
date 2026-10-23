<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('account_title', 150);
            $table->string('account_number', 100);
            $table->string('iban', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('branch_code', 50)->nullable();
            $table->text('instructions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_methods');
    }
};
