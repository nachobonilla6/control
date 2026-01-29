<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('color')->default('gray');
            $table->timestamps();
        });

        // Insert statuses that actually exist in the system
        DB::table('client_statuses')->insert([
            ['name' => 'extracted', 'label' => 'EXTRACTED', 'color' => 'yellow', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'created', 'label' => 'CREATED', 'color' => 'cyan', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'queued', 'label' => 'QUEUED', 'color' => 'blue', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sent', 'label' => 'SENT', 'color' => 'green', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'contacted', 'label' => 'CONTACTED', 'color' => 'indigo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'interested', 'label' => 'INTERESTED', 'color' => 'purple', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'negotiating', 'label' => 'NEGOTIATING', 'color' => 'violet', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'converted', 'label' => 'CONVERTED', 'color' => 'emerald', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'rejected', 'label' => 'REJECTED', 'color' => 'red', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_statuses');
    }
};

