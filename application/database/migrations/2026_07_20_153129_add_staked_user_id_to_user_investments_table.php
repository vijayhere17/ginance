<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('user_investments', function (Blueprint $table) {

        $table->unsignedBigInteger('staked_user_id')
              ->nullable()
              ->after('user_id');

        $table->index('staked_user_id');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::table('user_investments', function (Blueprint $table) {

        $table->dropIndex(['staked_user_id']);
        $table->dropColumn('staked_user_id');

    });
}
};
