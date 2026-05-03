<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->string('stripe_session_id')->nullable()->unique()->after('payment_status');
            $table->unsignedInteger('download_count')->default(0)->after('stripe_session_id');
            $table->timestamp('last_downloaded_at')->nullable()->after('download_count');
            $table->string('last_download_ip')->nullable()->after('last_downloaded_at');
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropUnique(['stripe_session_id']);
            $table->dropColumn([
                'stripe_session_id',
                'download_count',
                'last_downloaded_at',
                'last_download_ip',
            ]);
        });
    }
};
