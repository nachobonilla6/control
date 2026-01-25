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
        Schema::create('projects', function (Blueprint $col) {
            $col->id();
            $col->string('name');
            $col->text('description')->nullable();
            $col->string('type'); // e.g., Automation, Web, CRM
            $col->boolean('active')->default(true);
            $col->json('images')->nullable(); // Stores array of paths
            $col->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
