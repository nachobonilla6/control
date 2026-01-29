<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'extracted', 'label' => 'EXTRACTED', 'color' => 'yellow'],
            ['name' => 'queued', 'label' => 'QUEUED', 'color' => 'blue'],
            ['name' => 'sent', 'label' => 'SENT', 'color' => 'green'],
            ['name' => 'contacted', 'label' => 'CONTACTED', 'color' => 'indigo'],
            ['name' => 'interested', 'label' => 'INTERESTED', 'color' => 'purple'],
            ['name' => 'negotiating', 'label' => 'NEGOTIATING', 'color' => 'violet'],
            ['name' => 'converted', 'label' => 'CONVERTED', 'color' => 'emerald'],
        ];

        foreach ($statuses as $status) {
            DB::table('client_statuses')->updateOrInsert(
                ['name' => $status['name']],
                [
                    'label' => $status['label'],
                    'color' => $status['color'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
