<?php

namespace Database\Seeders;

use App\Models\NotificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationType::insert([
            ['name' => 'info', 'color' => '#3498db'],      // Blue
            ['name' => 'warning', 'color' => '#f39c12'],   // Yellow
            ['name' => 'error', 'color' => '#e74c3c'],     // Red
            ['name' => 'success', 'color' => '#2ecc71'],   // Green
        ]);
    }
}
