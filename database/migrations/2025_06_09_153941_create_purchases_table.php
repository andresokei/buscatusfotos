<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('purchases', function (Blueprint $table) {
        $table->id();
        $table->string('email');
        $table->json('media_ids');
        $table->unsignedBigInteger('session_id')->nullable();
        $table->decimal('amount', 8, 2);
        $table->uuid('download_token')->nullable();
        $table->datetime('expires_at')->nullable();
        $table->enum('payment_status', ['pending', 'paid'])->default('pending');
        $table->timestamps();
        
        $table->foreign('session_id')->references('id')->on('sessions');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
