<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notice_types', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('color');
            $table->timestamps();
        });

        DB::table('notification_types')->insert([
            ['type' => 'announcement', 'color' => 'blue'],
            ['type' => 'information', 'color' => 'orange'],
            ['type' => 'outage', 'color' => 'red'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_types');
    }
};
