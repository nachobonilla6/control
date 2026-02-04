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
        DB::table('clients')->where('status', 'extracted')->update(['status' => 'queued']);
    }

    public function down(): void
    {
        DB::table('clients')->where('status', 'queued')->update(['status' => 'extracted']);
    }
};
