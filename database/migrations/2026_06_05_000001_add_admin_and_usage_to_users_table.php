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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_admin')->default(false)->after('is_subscribed');
            $table->unsignedInteger('pdf_generated_count')->default(0)->after('is_admin');
            $table->timestamp('last_generated_at')->nullable()->after('pdf_generated_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_admin', 'pdf_generated_count', 'last_generated_at']);
        });
    }
};
