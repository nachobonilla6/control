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
        Schema::create('client_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label');
            $table->string('color')->default('gray');
            $table->timestamps();
        });

        // Insert default statuses
        DB::table('client_statuses')->insert([
            ['name' => 'queued', 'label' => 'QUEUED', 'color' => 'yellow', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'sent', 'label' => 'SENT', 'color' => 'green', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'contacted', 'label' => 'CONTACTED', 'color' => 'blue', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'interested', 'label' => 'INTERESTED', 'color' => 'indigo', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'negotiating', 'label' => 'NEGOTIATING', 'color' => 'purple', 'created_at' => now(), 'updated_at' => now()],
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
