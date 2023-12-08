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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('order_amount_limit')->unsigned()->nullable();
            $table->text('order_processing_message');
        });

        DB::table('settings')->insert([
            'order_amount_limit' => 1000,
            'order_processing_message' => "You order is being processed. We will notify you as soon as it completes!",
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
