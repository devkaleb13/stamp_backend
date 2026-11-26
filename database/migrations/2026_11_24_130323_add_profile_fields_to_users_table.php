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
            $table->string('user_image')->nullable()->after('password');
            $table->boolean('is_active')->default(true)->after('user_image');
            $table->foreignId('role_id')->after('is_active')->constrained('roles');
        });
    }
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn(['user_image', 'is_active', 'role_id']);
        });
    }
};
