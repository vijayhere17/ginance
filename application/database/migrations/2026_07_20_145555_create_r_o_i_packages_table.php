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
        Schema::create('roi_packages', function (Blueprint $table) {
    $table->id();

    $table->decimal('min_amount',18,2);
    $table->decimal('max_amount',18,2)->nullable();

    $table->decimal('cap_multiplier',5,2);

    $table->boolean('status')->default(1);

    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_o_i_packages');
    }
};
