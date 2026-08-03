<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Nullable on purpose: bookings can still be made without picking a
            // specific table (e.g. the Google shortcut, or "no preference").
            $table->foreignId('table_id')->nullable()->after('guests')
                ->constrained('restaurant_tables')->nullOnDelete();
            $table->index(['date', 'time', 'table_id']);
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('table_id');
        });
    }
};
