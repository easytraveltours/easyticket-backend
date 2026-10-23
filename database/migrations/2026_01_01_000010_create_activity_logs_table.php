<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 150)->nullable();
            $table->string('user_role', 50)->nullable();
            $table->string('action', 100);
            $table->string('object_type', 50)->nullable();
            $table->string('object_id', 100)->nullable();
            $table->text('details')->nullable();
            $table->string('ip_address', 50)->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
