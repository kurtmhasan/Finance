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
        Schema::create('market_stocks', function (Blueprint $table) {
            $table->id();
            $table->string('symbol')->unique(); // THYAO (Unique index çok önemli)
            $table->string('name')->nullable();
            $table->decimal('current_price', 15, 2); // Anlık piyasa fiyatı
            $table->timestamp('last_fetched_at'); // Verinin tazelik zamanı
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_stocks');
    }
};
