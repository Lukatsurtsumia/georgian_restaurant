<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->string('label', 20);                 // e.g. "T1"
            $table->unsignedTinyInteger('seats');         // covers this table can hold
            $table->string('shape')->default('round');    // round | square
            $table->string('zone')->default('main');      // window | terrace | bar | main
            $table->unsignedTinyInteger('pos_x');         // 0-100 (% across the room)
            $table->unsignedTinyInteger('pos_y');         // 0-100 (% down the room)
            $table->unsignedSmallInteger('sort')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
