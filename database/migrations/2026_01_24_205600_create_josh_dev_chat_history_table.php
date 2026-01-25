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
        Schema::create('josh_dev_chat_history', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id', 100);
            $table->string('username', 150)->nullable();
            $table->enum('role', ['user', 'assistant', 'system']);
            $table->longText('message');
            $table->timestamp('created_at')->useCurrent();

            $table->index('chat_id');
            $table->index('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('josh_dev_chat_history');
    }
};
