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
       Schema::create('roi_income_history', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('user_id');

    $table->unsignedBigInteger('investment_id');

    $table->integer('cycle_no');

    $table->decimal('roi_percent',5,2);

    $table->decimal('roi_amount',18,2);

    $table->date('roi_date');

    $table->string('remarks')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('r_o_i_income_histories');
    }
};
