<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->decimal('total_biaya_sewa', 10, 2)->default(0)->after('keperluan');
            $table->decimal('total_deposit', 10, 2)->default(0)->after('total_biaya_sewa');
            $table->decimal('denda_keterlambatan', 10, 2)->default(0)->after('total_deposit');
            $table->decimal('total_pembayaran', 10, 2)->default(0)->after('denda_keterlambatan');
            $table->string('payment_status')->default('pending')->after('total_pembayaran'); // pending, paid, failed
            $table->string('midtrans_order_id')->nullable()->after('payment_status');
            $table->json('midtrans_response')->nullable()->after('midtrans_order_id');
            $table->timestamp('paid_at')->nullable()->after('midtrans_response');
        });
    }

    public function down(): void
    {
        Schema::table('peminjamans', function (Blueprint $table) {
            $table->dropColumn([
                'total_biaya_sewa', 
                'total_deposit', 
                'denda_keterlambatan', 
                'total_pembayaran',
                'payment_status',
                'midtrans_order_id',
                'midtrans_response',
                'paid_at'
            ]);
        });
    }
};