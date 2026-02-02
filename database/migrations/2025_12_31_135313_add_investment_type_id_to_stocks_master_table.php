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
        Schema::table('stocks_master', function (Blueprint $table) {
            $table->foreignId('investment_type_id')
                ->nullable()
                ->after('name')
                ->constrained('investment_types');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stocks_master', function (Blueprint $table) {
            $table->dropForeign(['investment_type_id']);
            $table->dropColumn('investment_type_id');
        });
    }
};
