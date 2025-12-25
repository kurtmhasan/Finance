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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Parayı gönderen (Eğer sistem yüklüyorsa null olabilir)
            $table->foreignId('sender_id')->nullable()->constrained('users')->onDelete('cascade');
            // Parayı alan
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');

            $table->decimal('amount', 15, 2); // Transfer edilen miktar
            $table->string('type'); // 'transfer', 'deposit', 'withdrawal' (enum da olabilir)
            $table->string('description')->nullable(); // "Kira bedeli", "Borç" vb.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
