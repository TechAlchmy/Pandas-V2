<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('api_calls')->truncate();
        Schema::table('api_calls', function (Blueprint $table) {
            $table->string('request_id');
            $table->integer('order_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('api_calls', function (Blueprint $table) {
            $table->dropColumn('request_id');
            $table->dropColumn('order_id');
        });
    }
};
