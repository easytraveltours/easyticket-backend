<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->decimal('discount_percent', 5, 2)->default(0.00);
            $table->decimal('credit_limit', 12, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_groups');
    }
};
