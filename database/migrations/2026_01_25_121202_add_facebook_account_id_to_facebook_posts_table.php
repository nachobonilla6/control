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
        Schema::table('facebook_posts', function (Blueprint $table) {
            $table->unsignedBigInteger('facebook_account_id')->nullable()->after('id');
            $table->foreign('facebook_account_id')->references('id')->on('facebook_accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('facebook_posts', function (Blueprint $table) {
            $table->dropForeign(['facebook_account_id']);
            $table->dropColumn('facebook_account_id');
        });
    }
};
