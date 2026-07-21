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
        Schema::create('user_investments', function (Blueprint $table) {

    $table->id();

    $table->unsignedBigInteger('user_id');

    $table->string('investment_no')->unique();

    $table->decimal('amount',18,2);

    $table->unsignedBigInteger('package_id');

    $table->decimal('cap_multiplier',5,2);

    $table->decimal('maximum_income',18,2);

    $table->decimal('total_roi_paid',18,2)->default(0);

    $table->decimal('remaining_income',18,2);

    $table->integer('current_cycle')->default(1);

    $table->decimal('current_roi_percent',5,2);

    $table->date('cycle_start_date');

    $table->date('cycle_end_date')->nullable();

    $table->integer('days_completed')->default(0);

    $table->enum('status',[
        'pending',
        'active',
        'completed',
        'cancelled'
    ])->default('active');

    $table->timestamp('completed_at')->nullable();

    $table->timestamps();

});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_investments');
    }
};
