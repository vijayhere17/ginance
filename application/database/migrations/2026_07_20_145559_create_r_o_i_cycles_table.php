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
        Schema::create('roi_cycles', function (Blueprint $table) {

    $table->id();

    $table->integer('cycle_no');

    $table->decimal('daily_roi',5,2);

    $table->integer('duration_days')->nullable();

    $table->boolean('is_lifetime')->default(0);

    $table->boolean('status')->default(1);

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_o_i_cycles');
    }
};
