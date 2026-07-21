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
        Schema::create('roi_cron_logs', function (Blueprint $table) {

    $table->id();

    $table->date('cron_date');

    $table->integer('processed')->default(0);

    $table->integer('success')->default(0);

    $table->integer('failed')->default(0);

    $table->text('remarks')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_o_i_cron_logs');
    }
};
