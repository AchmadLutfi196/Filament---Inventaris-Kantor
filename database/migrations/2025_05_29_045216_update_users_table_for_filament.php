<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan kolom yang diperlukan ada dan benar tipenya
            if (!Schema::hasColumn('users', 'email_verified_at')) {
                $table->timestamp('email_verified_at')->nullable();
            } else {
                // Ubah tipe jika salah
                $table->timestamp('email_verified_at')->nullable()->change();
            }
            
            if (!Schema::hasColumn('users', 'remember_token')) {
                $table->rememberToken();
            } else {
                // Pastikan tipe string dengan length 100
                $table->string('remember_token', 100)->nullable()->change();
            }
            
            // Tambahkan kolom role dan is_active jika belum ada
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }
            
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rollback jika diperlukan
        });
    }
};