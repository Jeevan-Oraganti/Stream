<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_sticky')->default(false);
            $table->unsignedBigInteger('notice_type_id');
            $table->dateTime('scheduled_at')->nullable();
            $table->dateTime('expiry_date')->nullable();
            $table->boolean('is_active')->default(false);
            $table->string('recurrence')->nullable(); // daily, weekly, monthly
            $table->json('recurrence_days')->nullable(); // For weekly recurrence (e.g., ["Monday", "Wednesday"])
            $table->timestamps();

            $table->foreign('notice_type_id')->references('id')->on('notice_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notices');
    }
};
