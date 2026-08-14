<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Free-text perk/discount the manager can attach to a booking
            // (e.g. "-10%", "welcome glass"). Shown to staff, not the guest.
            $table->string('discount', 120)->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('discount');
        });
    }
};
